<?php

namespace App\Http\Controllers\Adminfour;

use App\common\Curl;
use App\common\RedisKey;
use App\lib\func\show;
use App\Models\Admin\MemberReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Redis;
use Redirect;
use Input;
use Excel;
use Illuminate\Support\Facades\Session;
use DB;
use Config;
use App\Models\Admin\System;
use App\Models\Admin\AllReport;
use App\Models\Admin\RelationReport;
use App\Models\Admin\RelationMember;

class IndexController extends Controller
{
    //首页
    public function index(Request $request){

        if(!empty($request->siteId)){
            $this->checkSite($request->siteId);
        }

        $isexcel = '0';
        if($user = Auth::guard('member')->user()){
                $name = $user->username;
                $res = MemberReport::where('member_id',$user->id)->where('siteId',Session::get('credentials'))->get()->first();
                $tableauIds = explode(',',$res['tableauIds']);
                if($user->excel == '1'){
                    $isexcel = '1';
                }
                if($user->isInit == '1'){
                    return redirect('/adminfour/member/info');
                }
        }else{
            $tableauIds = false;
            $name = Auth::guard('admin')->user()->username;
            $isexcel = '1';

        }
        $type = Session::get('user_type');
        if($type == '2'){//用户登录
            $sis = MemberReport::where('member_id',$user->id)->get();
            $allSites = Session::get('allSites');
            $da = [];
            foreach ($allSites as $v){
                foreach ($sis as $vs){
                    if($v->id == $vs->siteId){
                        $da[] = $v;
                    }
                }
            }
            $allSites = $da;
            if(empty($da)){
                return view('admin4.error.index');
            }
            $this->checkSite($da[0]->id);
        }else{
            $allSites = Session::get('allSites');
        }
        $system = System::get()->first();
        /*获取用户的信息*/
//        $data = Curl::getWorkBooks();
        $data = json_decode(Redis::hget(RedisKey::$siteAllWork,Session::get('credentials'))) ?? Curl::getWorkBooks();
        if($data === false) {
            return view('admin4.error.index');
        }else{
            $p = [];
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
            $allreport = AllReport::where('siteId',Session::get('credentials'))->get();
            foreach($allreport as $all=>$repo){
                $repo->delete();
            }

            foreach($data as $key=>$val){
                $id = $val->project->id;
                $ress = json_decode(Redis::hget(RedisKey::$workAllPort,md5(Session::get('credentials') .''. $val->id,32))) ?? Curl::getWorkBooksViews($val->id);
//                $ress = Curl::getWorkBooksViews($val->id);
                if($ress ===false){
                    return view('admin4.error.index');
                }else{
                    if(!empty($ress)){
                        $ress = (object)$ress;
                        $viesdata = $ress->viesdata;
                        $wok = $ress->wok;
                        $ds = array();
                        foreach($viesdata as $keys => $vaies){//数据存入报表表
                            $ds['report_id'] = $vaies->id;
                            $ds['report_name'] = $vaies->name;
                            $ds['contentUrl'] = $vaies->contentUrl;
                            $ds['project_name'] = $wok->project->name;
                            $ds['workBook_name'] =  $wok->name;
                            $ds['workBook_id'] = $wok->id;
                            $ds['siteId'] = Session::get('credentials');
                            AllReport::insert($ds);
                        }
                    }


                    if($user){
                        $member_group = RelationReport::where('member_id',$user->id)->where('siteId',Session::get('credentials'))->get();
                    }
                    if(!$user || !$member_group){
                        for($i=0 ; $i< count($viesdata);$i++ ){
                            $viesdata[$i]->filter = "iframeSizedToWindow=true";
                        }
                    }
                }


                if($tableauIds){
                    $project = false;
                    foreach($viesdata as $key => $vaie){
                        if(in_array($vaie->id,$tableauIds)){
                            //判断该报表是属于用户
                            foreach($member_group as $o=>$me){
                                if($me->report_id == $vaie->id){
                                    if($me->usergroup && $me->usergroup->project_group){
                                        $project = explode('|',$me->usergroup->project_group);
                                        $project = implode('@',$project);
                                        $viesdata[$key]->filter = $project;
                                    }else{
                                        $project = explode('|',$me->project_group);
                                        $project = implode('@',$project);
                                        $viesdata[$key]->filter = $project;
                                    }
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
        return view('admin4.index.index',compact('p','system','type','allSites','name','isexcel'));
    }

    //站点切换
    public function checkSite($siteId)
    {
        $all = Session::get('allSites');
        foreach ($all as $v){
            if($v->id == $siteId){
                $site = $v;
                break;
            }
        }
        $system = System::get()->first();
        $res = Curl::getToken([
            'username' => $system->tableau_username,
            'password' => $system->tableau_password,
            'contentUrl' => $site->contentUrl
        ]);
        Session::put('token', $res->credentials->token);
        Session::put('credentials', $res->credentials->site->id);
    }




    //首页，框架页面
    public function welcome(){
        return view('admin4.index.welcome');
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
            return view('admin4.index.excel');
        }
    }


    public function cache(Request $request)
    {
        if($request->method() == 'GET'){
            return view('admin4.index.cache');
        }else if($request->method() == 'POST'){
            $res = $this->cacheUpdate();
            return $res?show::success('更新成功！'):show::error('更新失败，请重试！');
        }
    }

    public function cacheUpdate()
    {
        //所有站点信息
        $url = Session::get('tableau_domain') . '/api/3.2/sites';
        $response = Curl::send($url,['token' => Session::get('token')]);
        if($response === false){
            return false;
        }
        Session::put('allSites',$response->sites->site);
        Redis::set(RedisKey::$allSites,json_encode($response->sites->site));

        //站点下的报表
        $work = Curl::getWorkBooks(Session::get('credentials'));
        if($work === false){
            return false;
        }
        Redis::hset(RedisKey::$siteAllWork,Session::get('credentials'),json_encode($work));
        foreach($work as $value){
            $re = Curl::getWorkBooksViews($value->id,Session::get('credentials'));
            if($re === false){
                return false;
            }
            Redis::hset(RedisKey::$workAllPort,md5(Session::get('credentials') .''. $value->id,32),json_encode($re));
        }
        return true;
    }
}
