<?php

namespace App\Http\Controllers\Admin;

use App\common\Curl;
use App\common\RedisKey;
use App\lib\func\show;
use App\Models\Admin\Member;
use App\Models\Admin\SmsConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\System;
use App\Models\Admin\Manager;

//引入Auth门面
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use http\Cookie;
use Illuminate\Support\Facades\DB;

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
    public function login(Request $request){
        // 该页面使用H_ui.admin模板自动生成，需要到该网站下下载对应的代码，然后在public目录下引入他的静态资源，然后在视图文件中引入你需要的界面的代码，当前页面引入login.html的代码,并修改页面的资源引入路径为自己刚才引入资源后的资源路径
        $result = Manager::get()->first();
        if(!$result){
            return view('admin3.inization.index');//初始化基本信息
        }
        $system = System::get()->first();
        if(!$system){
            return view('admin3.inization.system');
        }
        $sy = SmsConfig::get()->first();
        if(!$sy){
            return view('admin3.inization.smsInit');
        }
        $notAuth = $request->notAuth;
        $tableau_domain = $system->system_domain;
        Session::put(['tableau_domain' => $tableau_domain]);
        return view('admin.public.login',compact('system','notAuth'));
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
    public function check(Request $request)
    {
        $isVisitor = $request->isVisitor;
        if(!$isVisitor){
            //开始自动验证
            $this->validate($request, [
                //验证语法  需要验证的字段名 => "验证规则1|验证规则2...."
                'username' => 'required',
                'password' => 'required',
                // 'captcha' => 'required|size:4|captcha'
            ]);
            $data = $request->only(['username', 'password']);
            //继续开始进行身份核实
            $data['status'] = '1';//要求状态为启用的用户登录
            $type = '1';
            $result = Auth::guard('admin')->attempt($data, $request->get('online'));
            if (!$result) {
                $result = Member::where([
                    'mobile' => $data['username'],
                    'status' => 1,
                ])->get();
                if($result->isEmpty() || !Hash::check($data['password'],$result->first()->password)){
                    return redirect('/')->withErrors([
                        'loginError' => '用户名或密码错误或未授权，请联系管理员。'
                    ]);
                }else{
                    Auth::guard('member')->login($result->first());
                    $type = '2';
                }
            }
            Session::put('user_type', $type);
            $system = System::get()->first();
            //跳转到后台首页
            $model = $system->model;
        }else{
            $system = System::get()->first();
            $memberId = $system['publicMemberId'];
            $result = Member::where('id',$memberId)->get();
            Auth::guard('member')->login($result->first());
            $type = '2';
            $model = $system->model;
            Session::put('user_type', $type);
            Session::put('isVisitor', true);
        }
        $username = $system->tableau_username;
        $password = $system->tableau_password;

        //判断是否成功
        if ($result) {
            $res = Curl::getToken(['username' => $username,'password' => $password,'contentUrl' => null]);
            if ($res === false) {
                return view('admin.error.index');
            } else {
                Session::put('token', $res->credentials->token);
                Session::put('credentials', $res->credentials->site->id);
                Session::put('default_credentials', $res->credentials->site->id);
            }
            //获取所有站点
            if(!($allSites = json_decode(Redis::get(RedisKey::$allSites)))){
                $url = Session::get('tableau_domain') . '/api/3.2/sites';
                $response = Curl::send($url,['token' => Session::get('token')]);
                if($response === false)
                    return view('admin.error.index');
                Session::put('allSites',$response->sites->site);//所有站点信息
                Redis::set(RedisKey::$allSites,json_encode($response->sites->site));
            }else{
                Session::put('allSites',$allSites);//所有站点信息
            }


            if($isVisitor){
                switch ($model){
                    case '1':
                        $url = '/admin/index/index';
                        break;
                    case '2':
                        $url = '/admintwo/index/index';
                        break;
                    case '3':
                        $url = '/adminthree/index/index';
                        break;
                    case '4':
                        $url = '/adminfour/index/index';
                        break;
                }
                return show::success('OK',200, $url);
            }
            if ($model == '1') {
                return redirect('admin/index/index');
            }
            if ($model == '2') {
                return redirect('admintwo/index/index');
            }
            if ($model == '3') {
                return redirect('adminthree/index/index');
            }
            if ($model == '4') {
                return redirect('adminfour/index/index');
            }
        } else {
            //withErrors表示带上错误信息
            return redirect('/')->withErrors([
                'loginError' => '用户名或密码错误或未授权，请联系管理员。'
            ]);
        }
    }

    //短信验证码登录
    public function smsLogin()
    {
        $system = System::get()->first();
        return view('admin.public.sms',compact('system'));
    }

    //游客登录
    public function visitorLogin()
    {
        $system = System::get()->first();
        return view('admin.public.visitor',compact('system'));
    }

    //注册
    public function register()
    {
        return view('admin.public.register');

    }

    public function registerCheck(Request $request)
    {
        $this->validate($request,[
            'username' => 'required',
            'company_name' => 'required',
            'captch' => 'required',
            'email' => 'email',
            'mobile' => 'regex:/^1[345789][0-9]{9}$/',
            'password' => 'required|confirmed:password_confirmation'
        ]);
        $re = $request->except('_token');
        $re['password'] = bcrypt($re['password']);
        $re['status'] = 1;
        unset($re['password_confirmation']);
        $captch = $re['captch'];
        unset($re['captch']);
//        $ca = cache()->store('file')->get($re['mobile']);
//        if($captch != $ca){
//            return show::error('验证码错误，请重试！');
//        }
        $man = Member::where('mobile',$re['mobile'])->get();
        if(!$man->isEmpty()){
            return show::error('该手机号已经存在！');
        }
        $man = Member::where('email',$re['email'])->get();
        if(!$man->isEmpty()){
            return show::error('该邮箱已经存在！');
        }
        try {
            $time = time();
            $re['created_at'] = $time;
            $re['updated_at'] = $time;
            $res = Member::create($re);
            if($res){
                $system = System::get()->first();
                if(Session::get('isVisitor')){
                    Auth::guard('member') ->logout();
                    Session::flush();
                }
                return Show::success('OK',200,$system);
            }else{
                return Show::success('注册失败');
            }
        }catch (\Exception $e){
            return Show::success('注册失败');
        }

    }


    //用户退出
    public function logout(Request $request){
        //退出,会清除用户信息
        if(Session::get('user_type') == '1')
            Auth::guard('admin') -> logout();
        else if(Session::get('user_type') == '2'){
            Auth::guard('member') -> logout();
        }
        Session::flush();
        //跳转到登录界面
        return redirect('/');
    }

}
