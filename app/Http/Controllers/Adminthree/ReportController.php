<?php

namespace App\Http\Controllers\Adminthree;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Input;
use DB;
use Auth;
use Session;
use App\Models\Admin\System;
use App\Models\Admin\RelationReport;

class ReportController extends Controller
{

    public function index(){
        /*拿到所有报表的数据*/
        $curlt = curl_init();

        curl_setopt_array($curlt, array(
        CURLOPT_URL =>  Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/workbooks/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_COOKIE =>"token=".Session::get('token'),
        CURLOPT_HTTPHEADER => array(
            "X-Tableau-Auth: ".Session::get('token'),
            "Accept: application/json",
          ),
        ));
        $response = curl_exec($curlt);
        if(!$response) {
                return view('admin3.error.index');
        }
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          // $response = simplexml_load_string($response);
            $data = json_decode($response)->workbooks->workbook;
            $p = [];
            $pageUrlIds=[];
            // $rs = $response->toArray();
            foreach($data as $key=>$val){
                $id = $val->project->id;
                $curlt = curl_init();
                curl_setopt_array($curlt, array(
                CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/workbooks/".$val->id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "X-Tableau-Auth:".Session::get('token'),
                    "Accept: application/json",
                  ),
                ));
                $chilresponse = curl_exec($curlt);
                if(!$chilresponse) {
                    return view('admin3.error.index');
                }
                $err = curl_error($curlt);
                curl_close($curlt);
                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                    $viesdata = json_decode($chilresponse)->workbook->views->view;
                    $wok = json_decode($chilresponse)->workbook;
                }
                for($i=0 ; $i< count($viesdata);$i++ ){
                    $vies['view'] = $viesdata[$i];
                    $vies['project'] = $wok->project->name;
                    $vies['workBook'] = $wok->name;
                    $p[] = $vies;
                }
            }
        }
        $data = RelationReport::where('expire','>=',time())->orderBy('id','desc')->get();
        foreach($data as $o => $vl){
            $k = false;
            foreach($p as $pk=>$valu){
                if($vl->report_id == $valu['view']->id){
                    $k = true;
                    break;
                }
            }
            if(!$k){
                $val->delete();
            }
        }
        // foreach($p as $u => $l){
        //     $h = false;
        //     foreach($data as $g=>->$r){
        //         if($l['view']->id == $r->report_id){
        //             $h = true;
        //         }
        //     }
        //     if(!$h){
        //         $reportData['report_name'] = $l['view']->name;
        //         $reportData['project_name'] = $l['project'];
        //         $reportData['workBook_name'] = $l['workBook'];
        //         $reportData['report_id'] = $l['view']->id;
        //         $reportData['created_at'] = date('Y-m-d H:i:s',time());
        //         RelationReport::insert($reportData);
        //     }
        // }
        $date = RelationReport::all();
        foreach($date as $key=>$value){
            $date[$key]['username'] = $date[$key]->member->username;
        }
        return view('admin3.report.reportindex',compact('date'));
    }

   public function relation($id){
        if(Input::method() == 'POST'){
            $project_group = Input::get('project_group');
            foreach($project_group as $key=>$value){
                if($value == null){
                    unset($project_group[$key]);
                }
            }
            $project_group = implode("|",$project_group);
            $data['project_group'] = $project_group;
            $re = RelationReport::where('id',$id)->get()->first();
            if($re){
                $result = $re->update($data);
            }else{
                return '0';
            }
            return $result ? '1':'0';
        }else{

            $da = RelationReport::where('id',$id)->get()->first();
            if(!$da){
                return view('admin3.report.index');
            }else{
                $project_group = explode('|',$da->project_group);

            }
            return view('admin3.report.relation',compact('project_group'));
        }
   }


   public function relations($id){
        if(Input::method() == 'POST'){
            $ids = explode(',',$id);
            $project_group = Input::get('project_group');
            foreach($project_group as $key=>$value){
                if($value == null){
                    unset($project_group[$key]);
                }
            }
            $project_group = implode("|",$project_group);
            $data['project_group'] = $project_group;
            $result = true;
            foreach($ids as $k=>$id){
                $re = RelationReport::where('id',$id)->get()->first();
                if($re){
                    $result = $re->update($data);
                }else{
                    return '0';
                }
            }
            return $result ? '1':'0';
        }else{
            return view('admin3.report.index');
        }
   }

    public function expires($id){
        if(Input::method() == 'POST'){
            $ids = explode(',',$id);
            $expire = Input::get('expire');
            DB::beginTransaction();
            try {
                foreach($ids as $k=>$id){
                    $re = RelationReport::where('id',$id)->get()->first();
                    if($re){
                        $result = $re->update([
                            'expire' => time()+3600*$expire
                        ]);
                        if(!$result){
                            DB::rollback();
                            return '0';
                        }
                    }else{
                        DB::rollback();
                        return '0';
                    }
                }
                DB::commit();
                return '1';
            }catch (\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return '0';
            }
        }else{
            return view('admin3.report.expires');
        }
    }


   //报表位置的查询
   public function select(){

        if($user = Auth::guard('member')->user()){
                $name = $user->username;
                $tableauIds = explode(',',$user -> tableauIds);
        }else{
            $tableauIds = false;
            $name = Auth::guard('admin')->user()->username;

        }
        /*拿到所有报表的数据*/
        $curlt = curl_init();

        curl_setopt_array($curlt, array(
        CURLOPT_URL =>  Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/workbooks/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_COOKIE =>"token=".Session::get('token'),
        CURLOPT_HTTPHEADER => array(
            "X-Tableau-Auth: ".Session::get('token'),
            "Accept: application/json",
          ),
        ));
        $response = curl_exec($curlt);
        if(!$response) {
                return view('admin3.error.index');
        }
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          // $response = simplexml_load_string($response);
            $data = json_decode($response)->workbooks->workbook;
            $p = [];
            $pageUrlIds=[];
            // $rs = $response->toArray();
            foreach($data as $key=>$val){
                $id = $val->project->id;
                $curlt = curl_init();
                curl_setopt_array($curlt, array(
                CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/workbooks/".$val->id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "X-Tableau-Auth:".Session::get('token'),
                    "Accept: application/json",
                  ),
                ));
                $chilresponse = curl_exec($curlt);
                if(!$chilresponse) {
                    return view('admin3.error.index');
                }
                $err = curl_error($curlt);
                curl_close($curlt);
                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                    $viesdata = json_decode($chilresponse)->workbook->views->view;
                    $wok = json_decode($chilresponse)->workbook;
                }

                if($tableauIds){
                    foreach($viesdata as $key => $vaie){
                        if(in_array($vaie->id,$tableauIds) && RelationReport::where('report_id',$vaie->id)->where('member_id',$user->id)->where('expire','>=',time())->orWhere('expire',-1)->get()->first()){
                            $project = true;
                        }else{
                            unset($viesdata[$key]);//剔除该元素
                        }
                    }
                }
                foreach($viesdata as $key => $vaie){
                    if($user){
                        $report = RelationReport::where('report_id',$viesdata[$key]->id)->where('member_id',$user->id)->get()->first();
                        if(!$report) {
                            $vies['filter'] = '';
                        }else{
                            $project = explode('|',$report->project_group);
                            $vies['filter'] = implode('@',$project);
                        }
                    }else{
                        $vies['filter'] = "iframeSizedToWindow=true";
                    }
                    $vies['view'] = $viesdata[$key];
                    $vies['project'] = $wok->project->name;
                    $vies['workBook'] = $wok->name;
                    $p[] = $vies;
                }
            }
        }

        return view('admin3.report.select',compact('p'));
   }
}
