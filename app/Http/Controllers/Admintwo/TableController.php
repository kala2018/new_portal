<?php

namespace App\Http\Controllers\Admintwo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use Input;
use App\Models\Admin\Member;
use App\Models\Admin\Manager;


class TableController extends Controller
{
    public function index(Request $request){

        $name = Manager::get()->first();

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => Session::get('tableau_domain')."/trusted?username=".$name->username,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"username\":\"admin\"}",
        CURLOPT_HTTPHEADER => array(
            "User-Agent: TabCommunicate",
            "Content-Type: application/json",
            "Accept: application/json",
          ),
        ));
        $response = curl_exec($curl);
        if(!$response) {
            return view('admin2.error.index');
        }
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
            Session::put("ticket",$response);
        }

        $contentUrl = $request->all()['contentUrl'];
        $array = explode("/", $contentUrl);
        array_splice($array,1,1);
        $contentUrl = implode("/", $array);
        $ticket = Session::get('ticket');
        return view('admin2.table.index',compact('contentUrl','ticket'));
    }

    public function status(){
        $data = Input::all();
        // dd($data);
        if($data['type'] == '1'){
            //创建table用户
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/users",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "<tsRequest><user name=\"" . $data['username'] ."\" siteRole=\"Interactor\" authSetting=\"ServerDefault\" /></tsRequest>",
            CURLOPT_HTTPHEADER => array(
                "X-Tableau-Auth: ". Session::get('token'),
                "Accept: application/json",
              ),
            ));
            $response = curl_exec($curl);
            if(!$response) {
                return view('admin2.error.index');
            }
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
              return '0';
            } else {
                $res = json_decode($response);
                $result = Member::where('id',$data['id'])->get()->first();
                $result['tableau_id'] = $res->user->id;
                $result->status = 1;
                $result->save();

                return '1';
            }
        }else{
            // // dd($data);
            // //删除用户
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/users/".$data['tableau_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "X-Tableau-Auth: ".Session::get('token'),
                "Accept: application/json",
              ),
            ));
            $response = curl_exec($curl);
            if(!$response) {
                return view('admin2.error.index');
            }
            $err = curl_error($curl);
            curl_close($curl);
            $result = Member::where('tableau_id',$data['tableau_id'])->get()->first();
            $result['tableau_id'] = null;
            $result->status = 2;
            $result->save();
            return '1';
        }
    }

    //报表权限的分配
    public function auth($id){
        $user = Member::where('id',$id)->get()->first();
        if(Input::method() == 'POST'){
            $tableauIds = Input::get('tableauIds');
            $stringIds = implode(',',$tableauIds);
            $user -> tableauIds = $stringIds;
            $result = $user -> save();
            return $result ? '1':'0';
        }else{
            /*拿到所有报表的数据*/
            $curlt = curl_init();

            /*获取用户的信息*/
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
                return view('admin2.error.index');
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
                        return view('admin2.error.index');
                    }
                    $err = curl_error($curlt);
                    curl_close($curlt);
                    if ($err) {
                      echo "cURL Error #:" . $err;
                    } else {
                        $viesdata = json_decode($chilresponse)->workbook->views->view;
                    }

                    //判断是否是重复的父类
                    if(!array_key_exists($id,$p)){
                        $p[$id]["name"] = $val->project->name;
                    }
                    $p[$id]["project"][$val->id] = [
                    "webpageUrl" =>$val->webpageUrl,
                    "name" => $val->name,
                    "id" => $val->id,
                    "views" => $viesdata
                    ];
                }
            }
            $hasTableauIds = explode(',',$user->tableauIds);
            return view('admin2.table.authIndex',compact('p','hasTableauIds'));//展示报表列表
        }
    }
}
