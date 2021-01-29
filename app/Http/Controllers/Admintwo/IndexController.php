<?php

namespace App\Http\Controllers\Admintwo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Redirect;
use Session;
use DB;
use Input;
use Config;
use Excel;
use App\Models\Admin\System;
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
            if(!$data) return view('admin2.error.index');
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
                if($tableauIds){
                    $project = false;
                    foreach($viesdata as $key => $vaie){
                        if(in_array($vaie->contentUrl,$tableauIds)){
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
        return view('admin2.index.index',compact('p','system','type','name'));
    }




    //首页，框架页面
    public function welcome(){
        return view('admin2.index.welcome');
    }


        //excel数据的导入
    public function excel(Request $request){
        if(Input::method() == 'POST'){
            $host = $request->host;
            $username = $request->user;
            $password = $request->password;
            $port = $request->port;
            $database_name = $request->database_name;
            $table_name = $request->table_name;
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
            return view('admin3.index.excel');
        }
    }
}
