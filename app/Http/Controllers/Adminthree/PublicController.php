<?php

namespace App\Http\Controllers\Adminthree;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\System;
use App\Models\Admin\Manager;

//引入Auth门面
use Auth;
use Session;
use Cookie;
use DB;

class PublicController extends Controller
{
    // public function index(){
    //     if(Input::method() == 'POST'){
    //         $data = [

    //         ];
    //         $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

    //         $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

    //         $contentArray->transform(function ($item) use ($data){
    //             foreach ($data as $key => $value){
    //                 if(str_contains($item, $key)){
    //                 return $key . '=' . $value;
    //                 }
    //             }

    //             return $item;
    //         });

    //          $content = implode($contentArray->toArray(), "\n");

    //          \File::put($envPath, $content);
    //      }else{
    //         return view('admin.system.env');
    //      }
    // }


    //登陆界面的展示
    public function login(){

        // 该页面使用H_ui.admin模板自动生成，需要到该网站下下载对应的代码，然后在public目录下引入他的静态资源，然后在视图文件中引入你需要的界面的代码，当前页面引入login.html的代码,并修改页面的资源引入路径为自己刚才引入资源后的资源路径
        $system = System::get()->first();
        $tableau_domain = $system->system_domain;
        Session::put(['tableau_domain' => $tableau_domain]);
        return view('admin3.public.login',compact('system'));
    }


    // //tableau授权票证的获取
    // public function tableau($url,$methods,$data = null,$header){
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //     CURLOPT_URL => $url,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => "",
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 30,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => $methods,
    //     CURLOPT_POSTFIELDS => $data,
    //     CURLOPT_HTTPHEADER => $header
    //     ));
    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);
    //     curl_close($curl);
    //     if ($err) {
    //       echo "cURL Error #:" . $err;
    //     } else {
    //         $data = json_decode($response);
    //         return $data;
    //     }
    // }


    //后台登录验证
    public function check(Request $request){
        //开始自动验证
        $this -> validate($request,[
            //验证语法  需要验证的字段名 => "验证规则1|验证规则2...."
            'username' => 'required|min:2|max:20',
            'password' => 'required|min:6',
            // 'captcha' => 'required|size:4|captcha'
        ]);
        $data = $request -> only(['username','password']);
        //继续开始进行身份核实
        $data['status'] = '1';//要求状态为启用的用户登录

        $admin = DB::table('manager') -> get() ->first();
        $type = '1';
        $result = Auth::guard('admin') -> attempt($data,$request -> get('online'));
        $tableau_name = $admin->username;
        if(!$result){

            $result = Auth::guard('member') -> attempt($data,$request -> get('online'));
            if(!$result){
                return redirect('/') -> withErrors([
                'loginError' => '用户名或密码错误或未授权，请联系管理员。'
                ]);
            }
            $h = Auth::guard('member')->user();
            $tableau_name = $h->tableau_id;
            $type = '2';
        }
        Session::put('user_type',$type);
        $system = System::get()->first();
        $username = $system->tableau_username;
        $password = $system->tableau_password;
        //判断是否成功
        if($result){
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/auth/signin",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"credentials\":{\"name\":\"" . $username . "\",\"password\":\"".$password."\",\"site\":{\"contentUrl\":\"\"}}}",
            CURLOPT_HTTPHEADER => array(
                "User-Agent: TabCommunicate",
                "Content-Type: application/json",
                "Accept: application/json",
              ),
            ));
            $response = curl_exec($curl);
            if(!$response) return view('admin3.error.index');
            $err = curl_error($curl);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
                $res = json_decode($response);
                Session::put('token',$res->credentials->token);
                Session::put('credentials',$res->credentials->site->id);
                /*获取用户列表*/

                curl_setopt_array($curl, array(
                CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/users",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "X-Tableau-Auth: ".Session::get('token'),
                    "Accept: application/json",
                  ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                  // dd(json_decode($response));
                  $user = json_decode($response)->users->user;
                  $boole = true;
                  foreach($user as $val){
                    if($tableau_name && $tableau_name == $val->name){
                        $boole = false;
                    }
                  }
                  if($boole){
                    Auth::guard('admin') -> logout();
                    Session::flush();
                    return view('admin3.error.index');
                  }
                }
            }
            //跳转到后台首页
            return redirect('adminthree/index/index');
        }else{
            //withErrors表示带上错误信息
            return redirect('/') -> withErrors([
                'loginError' => '用户名或密码错误或未授权，请联系管理员。'
            ]);
        }
    }


    //用户退出
    public function logout(Request $request){
        //退出,会清除用户信息
        Auth::guard('admin') -> logout();
        Session::flush();


        //跳转到登录界面
        return redirect('/');
    }

}
