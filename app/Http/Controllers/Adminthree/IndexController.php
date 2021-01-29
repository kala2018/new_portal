<?php

namespace App\Http\Controllers\Adminthree;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Redirect;
use Input;
use Excel;
use Session;
use DB;
use Config;
use App\Models\Admin\System;
use App\Models\Admin\RelationReport;
use App\Models\Admin\RelationMember;

class IndexController extends Controller
{


    //首页
    public function index(){

        if($user = Auth::guard('member')->user()){
            $name = $user->username;
            $tableauIds = explode(',',$user -> tableauIds);
        }else{
            $tableauIds = false;
            $name = Auth::guard('admin')->user()->username;
        }
        $type = Session::get('user_type');
        $system = System::get()->first();
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
            if(!$data) return view('admin3.error.index');
            // $u = array();
            // if($tableauIds){
            //     foreach($data as $k=>$tab){
            //         if(in_array($tab->id,$tableauIds)){
            //             $u[] = $tab;
            //         }
            //     }
            //     $data = $u;
            // }
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
                    if($user){
                        $member_group = RelationReport::where('member_id',$user->id)->get();
                    }
                    if(!$user || !$member_group){
                        for($i=0 ; $i< count($viesdata);$i++ ){
                            $viesdata[$i]->filter = "iframeSizedToWindow=true";
                        }
                    }
                    // $dat = RelationReport::all();
                    // for($i=0 ; $i< count($viesdata);$i++ ){
                    //     if(json_decode($dat) == null){
                    //         $viesdata[$i]->filter = "iframeSizedToWindow=true";
                    //         continue;
                    //     }
                    //     foreach($dat as $g=>$r){
                    //         $viesdata[$i]->filter = "iframeSizedToWindow=true";
                    //         // if($viesdata[$i]->id == $r->report_id){
                    //         //     if($r->project_group){
                    //         //         if($user){
                    //         //             $pror = RelationMember::where('member_id',$user->id)->get()->first()->project_group;
                    //         //             $pro = explode('|',$r->project_group);
                    //         //             $pror = explode('|',$pror);

                    //         //             /*
                    //         //             *进行用户与报表项目组的组合
                    //         //             *
                    //         //             */
                    //         //             foreach($pro as $po=>$proj){
                    //         //                 $u = false;
                    //         //                 $pj = null;
                    //         //                 foreach($pror as $po2=>$proj2){
                    //         //                     $pl = explode('=',$proj);
                    //         //                     if($pl[0] == explode('=',$proj2)[0]){
                    //         //                         $pl[1] = $pl[1] .',' . explode('=',$proj2)[1];
                    //         //                         $u = true;
                    //         //                         $pj = $po2;
                    //         //                         $pro[$po] = implode('=',$pl);
                    //         //                         break;
                    //         //                     }
                    //         //                 }
                    //         //                 if($u){
                    //         //                     $pror[$pj] = $pro[$po];
                    //         //                 }else{
                    //         //                     $pror[] = $proj;
                    //         //                 }
                    //         //             }
                    //         //             $r->project_group = $pror;
                    //         //             $viesdata[$i]->filter = implode('&',$r->project_group);
                    //         //         }
                    //         //     }else{
                    //             // }
                    //         }
                    //     }
                    // }
                }

                if($tableauIds){
                    $project = false;
                    foreach($viesdata as $key => $vaie){
                        if(in_array($vaie->id,$tableauIds)){
                            foreach($member_group as $o=>$me){
                                if($me->report_id == $vaie->id){
                                    $project = explode('|',$me->project_group);
                                    $project = implode('@',$project);
                                    $viesdata[$key]->filter = $project;
                                }
                            }
                            $project = true;
                        }else{
                            unset($viesdata[$key]);//剔除该元素
                        }
                    }
                }else{
                    $project = true;
                }
                if($project){
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
        }
        // FS1Wu4GJRVCaNdtzbAeHlw|j9JPkfLMU0wZtx8c1BB6pkPGuiEim0h
        return view('admin3.index.index',compact('p','system','type','name'));
    }




    //首页，框架页面
    public function welcome(){
        return view('admin3.index.welcome');
    }


    //excel数据的导入
    public function excel(Request $request){
        if(Input::method() == 'POST'){
            $type = Input::get('type');
            $host = $request->host;
            $username = $request->user;
            $password = $request->password;
            $port = $request->port;
            $database_name = $request->database_name;
            $table_name = $request->table_name;
            if($type == '1'){
                Config::set(['database.connections.onlymysql.database'=>$database_name]);
                Config::set(['database.connections.onlymysql.host'=>$host]);
                Config::set(['database.connections.onlymysql.port'=>$port]);
                Config::set(['database.connections.onlymysql.username'=>$username]);
                Config::set(['database.connections.onlymysql.password'=>$password]);
                DB::purge('onlymysql');
                 //设置文件后缀白名单
                $allowExt   = ["csv", "xls", "xlsx"];
                //获取文件
                $file = $request->file('file');
                // $realPath = $file->getRealPath();
                $entension =  $file ->getClientOriginalExtension(); //上传文件的后缀.
                //校验文件
                if(isset($file) && $file->isValid()){
                    //判断是否是Excel
                    if(empty($entension) or in_array(strtolower($entension),$allowExt) === false){
                        return $this->fail(400, '不允许的文件类型');
                    }
                }
                $tabl_name = date('YmdHis').mt_rand(100,999);
                $newName = $tabl_name.'.'.'xls';//$entension;
                $path = $file->move(public_path().'/uploads',$newName);
                $cretae_path = public_path().'/uploads/'.$newName;
                $error = array();
                $status = '1';
                $data = Excel::load($cretae_path)->get()->toArray();
                foreach($data as $key=>$val){
                    $p = '';
                    $value = '';
                    $v = '';
                    foreach($val as $k=>$va){
                        $p .= $k.'='.'\''.$va.'\''.' AND ';
                    }
                    $p =  substr($p,0,strlen($p)-4);
                    $res = DB::connection('onlymysql')->select("select * from ".$table_name." where ".$p);
                    if(!$res){
                        foreach($val as $k=>$va){
                            $v .= '`'.$k.'`'.',';
                            $value .= '\''.$va.'\''.',';
                        }
                        $v =  substr($v,0,strlen($v)-1);
                        $value =  substr($value,0,strlen($value)-1);
                        DB::connection('onlymysql')->insert("insert into ".$table_name." (".$v.") "."values(".$value.")");
                    }
                }
                unlink($cretae_path);//删除该文件
                $da['error'] = $error;
                $da['status'] = $status;
                return $da;
            }else if($type == '2'){
                Config::set(['database.connections.onlymysql.database'=>$database_name]);
                Config::set(['database.connections.onlymysql.host'=>$host]);
                Config::set(['database.connections.onlymysql.port'=>$port]);
                Config::set(['database.connections.onlymysql.username'=>$username]);
                Config::set(['database.connections.onlymysql.password'=>$password]);
                DB::purge('onlymysql');
                 //设置文件后缀白名单
                $allowExt   = ["csv", "xls", "xlsx"];
                //获取文件
                $file = $request->file('file');
                // $realPath = $file->getRealPath();
                $entension =  $file ->getClientOriginalExtension(); //上传文件的后缀.
                //校验文件
                if(isset($file) && $file->isValid()){
                    //判断是否是Excel
                    if(empty($entension) or in_array(strtolower($entension),$allowExt) === false){
                        return $this->fail(400, '不允许的文件类型');
                    }
                }
                $tabl_name = date('YmdHis').mt_rand(100,999);
                $newName = $tabl_name.'.'.'xls';//$entension;
                $path = $file->move(public_path().'/uploads',$newName);
                $cretae_path = public_path().'/uploads/'.$newName;
                $error = array();
                $status = '1';
                $data = Excel::load($cretae_path)->get()->toArray();
                foreach($data as $key=>$val){
                    $p = '';
                    $value = '';
                    foreach($val as $k=>$va){
                        $p .= '`'.$k.'`'.',';
                        $value .= '\''.$va.'\''.',';
                    }
                    $p =  substr($p,0,strlen($p)-1);
                    $value =  substr($value,0,strlen($value)-1);
                    DB::connection('onlymysql')->insert("insert into ".$table_name." (".$p.") "."values(".$value.")");
                }
                unlink($cretae_path);//删除该文件
                $da['error'] = $error;
                $da['status'] = $status;
                return $da;
            }else{
                Config::set(['database.connections.onlymysql.database'=>$database_name]);
                Config::set(['database.connections.onlymysql.host'=>$host]);
                Config::set(['database.connections.onlymysql.port'=>$port]);
                Config::set(['database.connections.onlymysql.username'=>$username]);
                Config::set(['database.connections.onlymysql.password'=>$password]);
                DB::purge('onlymysql');
                 //设置文件后缀白名单
                $allowExt   = ["csv", "xls", "xlsx"];
                //获取文件
                $file = $request->file('file');
                // $realPath = $file->getRealPath();
                $entension =  $file ->getClientOriginalExtension(); //上传文件的后缀.
                //校验文件
                if(isset($file) && $file->isValid()){
                    //判断是否是Excel
                    if(empty($entension) or in_array(strtolower($entension),$allowExt) === false){
                        return $this->fail(400, '不允许的文件类型');
                    }
                }
                $tabl_name = date('YmdHis').mt_rand(100,999);
                $newName = $tabl_name.'.'.'xls';//$entension;
                $path = $file->move(public_path().'/uploads',$newName);
                $cretae_path = public_path().'/uploads/'.$newName;
                $error = array();
                $status = '1';
                $data = Excel::load($cretae_path)->get()->toArray();
                DB::connection('onlymysql')->delete("delete from ".$table_name);
                foreach($data as $key=>$val){
                    $p = '';
                    $value = '';
                    $v = '';
                    foreach($val as $k=>$va){
                        $v .= '`'.$k.'`'.',';
                        $value .= '\''.$va.'\''.',';
                    }
                    $v =  substr($v,0,strlen($v)-1);
                    $value =  substr($value,0,strlen($value)-1);
                    DB::connection('onlymysql')->insert("insert into ".$table_name." (".$v.") "."values(".$value.")");
                }
                unlink($cretae_path);//删除该文件
                $da['error'] = $error;
                $da['status'] = $status;
                return $da;
            }

        }else{
            return view('admin3.index.excel');
        }
    }
}
