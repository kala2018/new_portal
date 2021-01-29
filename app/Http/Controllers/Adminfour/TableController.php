<?php

namespace App\Http\Controllers\Adminfour;

use App\common\Curl;
use App\common\RedisKey;
use App\Models\Admin\MemberReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Auth;
use Input;
use App\Models\Admin\Member;
use App\Models\Admin\Manager;
use App\Models\Admin\System;
use App\Models\Admin\Collection;
use App\Models\Admin\RelationReport;


class TableController extends Controller
{
    public function index(Request $request){
        // $name = Manager::get()->first();
        $name = Auth::guard('member')->user();
        if(!$name){
            $user = Auth::guard('admin')->user();
            if($user){
                $username = System::get()->first()->tableau_username;
            }
        }else{
            $username = $name->tableau_id;
        }

        $all = Session::get('allSites');
        foreach ($all as $v){
            if($v->id == Session::get('credentials')){
                $site = $v;
                break;
            }
        }
        $site_root = $site->contentUrl;
        $url =  Session::get('tableau_domain')."/trusted";
        if(Session::get('default_credentials') != Session::get('credentials')){
            $site_root = '/t/'.$site_root;
        }

        // echo "".$username."and".Session::get('tableau_domain');
        // dd(Session::get('tableau_domain'));
        $response = Curl::tj_post($url,"username=".$username."&target_site=".$site->contentUrl);
//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//        CURLOPT_URL => $url,
//        CURLOPT_RETURNTRANSFER => true,
//        CURLOPT_ENCODING => "",
//        CURLOPT_MAXREDIRS => 10,
//        CURLOPT_TIMEOUT => 30,
//        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//        CURLOPT_CUSTOMREQUEST => "POST",
////        CURLOPT_POSTFIELDS => "{\"username\":\"".$username."}",
//        CURLOPT_HTTPHEADER => array(
//            "X-Tableau-Auth: " . Session::get('token'),
//            "User-Agent: TabCommunicate",
//            "Content-Type: application/json",
//            "Accept: application/json",
//          ),
//        ));
//        //设置post数据
//        $post_data = [
//            'username' => $username,
//            'target_site' => Session::get('credentials')
//        ];
//        var_dump(Session::get('token'));
//
//        return;
//        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
//        $response = curl_exec($curl);
        // dd($response);
        if(!$response) {
            return view('admin4.error.index');
        }
        $ticket = $response;

        // $contentUrl = $request->all()['contentUrl'];
//        var_dump($ticket);
//        return;
        $hascontentUrl = $request->contentUrl;
        if(!$request->filter){
          $filter = '';
        }else{
          $filter = implode('&',explode('@',$request->filter));
        }
        $array = explode("/", $hascontentUrl);
        array_splice($array,1,1);
        $contentUrl = implode("/", $array);
        // $ticket = Session::get('ticket');
        // dd($ticket);
        $report_id = $request->id;
        $system = System::get()->first();
        $toolbar =$system ->toolbar;

        return view('admin4.table.index',compact('site_root','contentUrl','ticket','filter','toolbar','system','report_id','hascontentUrl'));
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
        $group = RelationReport::where('siteId',Session::get('credentials'))->where('member_id',$user->id)->get();
        if(Input::method() == 'POST'){
            $tableauIds = Input::get('tableauIds');
            if($tableauIds){
                $hasTableauIds = $tableauIds;
            }else{
                $hasTableauIds = array();
            }

             /*拿到所有报表的数据*/
            $data = json_decode(Redis::hget(RedisKey::$siteAllWork,Session::get('credentials'))) ?? Curl::getWorkBooks();
            if($data === false){
                return view('admin4.error.index');
            }else{
                $p = [];
                $pageUrlIds=[];
                // $rs = $response->toArray();
                foreach($data as $key=>$val){
                    $res = json_decode(Redis::hget(RedisKey::$workAllPort,md5(Session::get('credentials') .''. $val->id,32)))??Curl::getWorkBooksViews($val->id);
                    if($res === false){
                         return view('admin4.error.index');
                    }else{
                        $res = (object)$res;
                        $viesdata = $res->viesdata;
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
            $insert = array();
            foreach($p as $k=>$value){
                foreach($value['project'] as $VieValue){
                    foreach($VieValue['views'] as $view){
                        if(in_array($view->id,$hasTableauIds)){
                            $bo = true;
                            foreach($group as $g=>$up){
                                if($up->report_id == $view->id){
                                    $bo = false;
                                    break;
                                }
                            }
                            if($bo){
                                $insert[$view->id]['project_name'] = $value['name'];
                                $insert[$view->id]['workBook_name'] = $VieValue['name'];
                                $insert[$view->id]['report_name'] = $view->name;
                                $insert[$view->id]['report_id'] = $view->id;
                                $insert[$view->id]['member_id'] = $user->id;
                                $insert[$view->id]['created_at'] = date('Y-m-d H:i:s',time());
                                $insert[$view->id]['updated_at'] = date('Y-m-d H:i:s',time());
                                $insert[$view->id]['siteId'] = Session::get('credentials');
                                if(Input::get('expire')){
                                    $insert[$view->id]['expire'] = time()+3600*Input::get('expire');
                                }

                            }
                        }
                    }
                }
            }
            foreach($group as $gk=>$gv){
                if(!in_array($gv->report_id,$hasTableauIds)){
                     RelationReport::find($gv->id)->delete();
                }
            }
            RelationReport::insert($insert);
            $havereport = Collection::where('siteId',Session::get('credentials'))->where('user_id',$user->id)->get();
            $in = RelationReport::where('siteId',Session::get('credentials'))->where('member_id',$user->id)->get();
            foreach($havereport as $p=>$vp){
                $isha = false;
                foreach($in as $i=>$iv){
                    // dd()
                    if($vp->report_id == $iv->report_id){
                        $isha = true;
                        break;
                    }
                }
                // dd($vp);
                if(!$isha){
                    Collection::where('id',$vp->id)->delete();
                }
            }
            $stringIds = implode(',',$hasTableauIds);
            $da = MemberReport::where('member_id',$user->id)->where('siteId',Session::get('credentials'))->get()->first();
            if(!$da){
                $result = MemberReport::create([
                    'member_id' => $user->id,
                    'siteId' => Session::get('credentials'),
                    'tableauIds' =>  $stringIds,
                ]);
            }else{
                $result = $da->update([
                    'tableauIds' => $stringIds
                ]);
            }
//            $user -> tableauIds = $stringIds;
//            $result = $user -> save();
            return $result ? '1':'0';
        }else{
            /*拿到所有报表的数据*/
            $data = json_decode(Redis::hget(RedisKey::$siteAllWork,Session::get('credentials'))) ?? Curl::getWorkBooks();
            if($data === false){
                return view('admin4.error.index');
            }else{
              // $response = simplexml_load_string($response);
                $p = [];
                $pageUrlIds=[];
                // $rs = $response->toArray();
                foreach($data as $key=>$val){
                    $id = $val->project->id;
                    $res = json_decode(Redis::hget(RedisKey::$workAllPort,md5(Session::get('credentials') .''. $val->id,32)))??Curl::getWorkBooksViews($val->id);
                    if($res === false){
                        return view('admin4.error.index');
                    }else{
                        $res = (object)$res;
                        $viesdata = $res->viesdata;
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
                $res = MemberReport::where('member_id',$user->id)->where('siteId',Session::get('credentials'))->get()->first();
                if(!empty($res)){
                    $hasTableauIds = explode(',',$res['tableauIds']);
                }else{
                    $hasTableauIds = [];
                }
//                $hasTableauIds = explode(',',$user->tableauIds);
                return view('admin4.table.authIndex',compact('p','hasTableauIds'));//展示报表列表
            }
        }
    }
    //批量报表权限的分配
    public function auths($ids){
        $id = explode(',',$ids);
        if(Input::method() == 'POST'){
            foreach($id as $key=>$id){
                $user = Member::where('id',$id)->get()->first();
                $group = RelationReport::where('siteId',Session::get('credentials'))->where('member_id',$user->id)->get();
                $tableauIds = Input::get('tableauIds');
                if($tableauIds){
                    $hasTableauIds = $tableauIds;
                }else{
                    $hasTableauIds = array();
                }

                 /*拿到所有报表的数据*/
                $curlt = curl_init();

                /*获取用户的信息*/
                $data = json_decode(Redis::hget(RedisKey::$siteAllWork,Session::get('credentials'))) ?? Curl::getWorkBooks();
                if($data === false){
                    return view('admin4.error.index');
                }else{
                    $p = [];
                    $pageUrlIds=[];
                    // $rs = $response->toArray();
                    foreach($data as $key=>$val){
                        $id = $val->project->id;
                        $res = json_decode(Redis::hget(RedisKey::$workAllPort,md5(Session::get('credentials') .''. $val->id,32)))??Curl::getWorkBooksViews($val->id);
                        if($res === false){
                            return view('admin4.error.index');
                        }else{
                            $res = (object)$res;
                            $viesdata = $res->viesdata;
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
                $insert = array();
                foreach($p as $k=>$value){
                    foreach($value['project'] as $VieValue){
                        foreach($VieValue['views'] as $view){
                            if(in_array($view->id,$hasTableauIds)){
                                $bo = true;
                                foreach($group as $g=>$up){
                                    if($up->report_id == $view->id){
                                        $bo = false;
                                        break;
                                    }
                                }
                                if($bo){
                                    $insert[$view->id]['project_name'] = $value['name'];
                                    $insert[$view->id]['workBook_name'] = $VieValue['name'];
                                    $insert[$view->id]['report_name'] = $view->name;
                                    $insert[$view->id]['report_id'] = $view->id;
                                    $insert[$view->id]['member_id'] = $user->id;
                                    $insert[$view->id]['siteId'] =Session::get('credentials');
                                    if(Input::get('expire')){
                                        $insert[$view->id]['expire'] = time()+3600*Input::get('expire');
                                    }
                                }
                            }
                        }
                    }
                }
                foreach($group as $gk=>$gv){
                    if(!in_array($gv->report_id,$hasTableauIds)){
                         RelationReport::find($gv->id)->delete();
                    }
                }

                RelationReport::insert($insert);
                $havereport = Collection::where('siteId',Session::get('credentials'))->where('user_id',$user->id)->get();
                $in = RelationReport::where('siteId',Session::get('credentials'))->where('member_id',$user->id)->get();
                foreach($havereport as $p=>$vp){
                    $isha = false;
                    foreach($in as $i=>$iv){
                        // dd()
                        if($vp->report_id == $iv->report_id){
                            $isha = true;
                            break;
                        }
                    }
                    // dd($vp);
                    if(!$isha){
                        Collection::where('id',$vp->id)->delete();
                    }
                }
                $stringIds = implode(',',$hasTableauIds);
                $da = MemberReport::where('member_id',$user->id)->where('siteId',Session::get('credentials'))->get()->first();
                if(!$da){
                    $result = MemberReport::create([
                        'member_id' => $user->id,
                        'siteId' => Session::get('credentials'),
                        'tableauIds' =>  $stringIds,
                    ]);
                }else{
                    $result = $da->update([
                        'tableauIds' => $stringIds
                    ]);
                }
            }
            return $result ? '1':'0';
        }else{
             /*拿到所有报表的数据*/
            $data = json_decode(Redis::hget(RedisKey::$siteAllWork,Session::get('credentials'))) ?? Curl::getWorkBooks();
            if($data === false){
                return view('admin4.error.index');
            }else{
                $p = [];
                $pageUrlIds=[];
                // $rs = $response->toArray();
                foreach($data as $key=>$val){
                    $id = $val->project->id;
                    $res = json_decode(Redis::hget(RedisKey::$workAllPort,md5(Session::get('credentials') .''. $val->id)))??Curl::getWorkBooksViews($val->id);
                    if($res === false){
                        return view('admin4.error.index');
                    }else{
                        $res = (object)$res;
                        $viesdata = $res->viesdata;
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
                return view('admin4.table.authsIndex',compact('p'));//展示报表列表
            }
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
            CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/users",
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
                return view('admin4.error.index');
            }
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              $tsResponse = json_decode($response)->users->user;
            }
            return view('admin4.table.user',compact('tsResponse','mamber'));
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
            CURLOPT_URL => Session::get('tableau_domain')."/".Session::get('credentials')."/users",
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
                return view('admin4.error.index');
            }
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              $tsResponse = json_decode($response)->users->user;
            }
            return view('admin4.table.users',compact('tsResponse','mamber'));
        }
    }
}
