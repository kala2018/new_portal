<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Auth;
use Input;
use App\Models\Admin\Member;
use App\Models\Admin\Manager;


class TableController extends Controller
{
    public function index(Request $request){

        // $name = Manager::get()->first();
        if(Session::get('isVisitor')){
            $username = Session::get('visitor_name');
        }else if(!$name = Auth::guard('member')->user()){
            $username = Auth::guard('admin')->user()->username;
        }else{
            $username = $name->tableau_id;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => Session::get('tableau_domain')."/trusted?username=".$username,
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
                return view('admin.error.index');
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
        return view('admin.table.index',compact('contentUrl','ticket'));
    }

    public function status(){
        $data = Input::get('id');
        $type = Input::get('type');
        $result = Member::where('id',$data)->get()->first();
        $result->status = $type;
        $res = $result->save();
        return $res?'1':'0';
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
                return view('admin.error.index');
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
                        return view('admin.error.index');
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
                $hasTableauIds = explode(',',$user->tableauIds);
                return view('admin.table.authIndex',compact('p','hasTableauIds'));//展示报表列表
            }
        }
    }
    //批量报表权限的分配
    public function auths($ids){
        $id = explode(',',$ids);
        if(Input::method() == 'POST'){
            foreach($id as $key=>$val){
                $user = Member::where('id',$val)->get()->first();
                $tableauIds = Input::get('tableauIds');
                $stringIds = implode(',',$tableauIds);
                $user -> tableauIds = $stringIds;
                $result = $user -> save();
            }
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
                return view('admin.error.index');
            }
            $err = curl_error($curlt);
            curl_close($curlt);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              // $response = simplexml_load_string($response);
                $data = json_decode($response)->workbooks->workbook;
            }
            return view('admin.table.authsIndex',compact('data'));//展示报表列表
        }
    }

    public function user($id){
        $mamber = Member::where('id',$id)->get()->first();
        if(Input::method() == 'POST'){
            $tableau_id = Input::get('tableauid');
            $mamber->tableau_id = $tableau_id;
            $result = $mamber->save();
            return $result ? '1':'0';
        }else{
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://tableau.kalaw.top/api/3.2/sites/".Session::get('credentials')."/users",
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
            $response = curl_exec($curl);
            if(!$response) {
                return view('admin.error.index');
            }
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              $tsResponse = json_decode($response)->users->user;
            }
            return view('admin.table.user',compact('tsResponse','mamber'));
        }
    }

    public function users($ids){
        $id = explode(',',$ids);
        if(Input::method() == 'POST'){
            foreach($id as $key=>$val){
                $mamber = Member::where('id',$val)->get()->first();
                $tableau_id = Input::get('tableauid');
                $mamber->tableau_id = $tableau_id;
                $result = $mamber->save();
            }
            return $result ? '1':'0';
        }else{
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://tableau.kalaw.top/api/3.2/sites/".Session::get('credentials')."/users",
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
            $response = curl_exec($curl);
            if(!$response) {
                return view('admin.error.index');
            }
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              $tsResponse = json_decode($response)->users->user;
            }
            return view('admin.table.users',compact('tsResponse','mamber'));
        }
    }
}
