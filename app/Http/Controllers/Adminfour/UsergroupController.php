<?php

namespace App\Http\Controllers\Adminfour;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\UserGroup;
use App\Models\Admin\RelationReport;
use Illuminate\Support\Facades\Session;
use Input;

class UsergroupController extends Controller
{
    //用户组操作
    public function group(){
        //查询数据
        $data = UserGroup::where('siteId',Session::get('credentials'))->get();
        return view('admin4.usergroup.index',compact('data'));
    }

    //添加用户组
    public function add(){
        if(Input::method() == 'POST'){
            $data['group_name'] = Input::get('group_name');
            $project_group = Input::get('project_group');
            foreach($project_group as $key=>$value){
                if($value == null){
                    unset($project_group[$key]);
                }
            }
            $project_group = implode("|",$project_group);
            $data['project_group'] = $project_group;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['siteId'] = Session::get('credentials');
            $status = '1';
            $error = array();
            $result = UserGroup::where('group_name',$data['group_name'])->get()->first();
            if($result){
                $error[] = $data['group_name'];
                $status = '0';
            }else{
                UserGroup::insert($data);
            }
            $da['error'] = $error;
            $da['status'] = $status;
            return $da;
        }else{
            return view('admin4.usergroup.add');
        }
    }

    //项目组映射
    public function report($id){
        if(Input::method() == 'POST'){
            $project_group = Input::get('project_group');
            foreach($project_group as $key=>$value){
                if($value == null){
                //排除空数组
                    unset($project_group[$key]);
                }
            }
            $project_group = implode("|",$project_group);
            $data['project_group'] = $project_group;
            $re = UserGroup::where('id',$id)->get()->first();
            if($re){
                $result = $re->update($data);
            }else{
                return '0';
            }
            return $result ? '1':'0';
        }else{
            $da = UserGroup::where('id',$id)->get()->first();
            if(!$da){
                return view('admin4.usergroup.report');
            }else{
                $project_group = explode('|',$da->project_group);

            }
            return view('admin4.usergroup.report',compact('project_group'));
        }
    }

    public function usergroup($id){
        if(Input::method() == 'POST'){
            $ids = explode(',',$id);
            foreach($ids as $i=>$d){
                $has = RelationReport::where('id',$d)->get()->first();
                $usergroup_id = Input::get('usergroup_id');
                $has->usergroup_id = $usergroup_id;
                $result = $has->save();
            }
            return $result ? '1' : '0';
        }else{
            $ids = explode(',',$id);
            if(count($ids)>1){
                $has = null;
            }else{
                $has = RelationReport::where('id',$id)->get()->first();
            }
            $data = UserGroup::get();
            return view('admin4.report.usergroup',compact('data','has'));
        }
    }

    //修改用户组信息
    public function modify($id){
        $data = UserGroup::where('id',$id)->get()->first();
        if(Input::method() == 'POST'){
            $dat['group_name'] = Input::get('group_name');
            $dat['project_group'] = implode('|',Input::get('project_group'));
            $error = array();
            $status = '1';
            $s = UserGroup::where('id','!=',$id)->where('group_name',$dat['group_name'])->get()->first();
            if($s){
                $error[] = $dat['group_name'];
                $status = '0';
                $da['error'] = $error;
                $da['status'] = $status;
                return $da;
            }
            $data->update($dat);
            $da['error'] = $error;
            $da['status'] = $status;
            return $da;
        }else{
            $project_group = explode('|',$data->project_group);
            return view('admin4.usergroup.modify',compact('data','project_group'));
        }
    }

    //删除用户组
    public function delete(){
        $id = Input::get('id');
        $data = RelationReport::where('usergroup_id',$id)->get();
        foreach($data as $key=>$value){
            $value->usergroup_id = null;
            $value->save();
        }
        $result = UserGroup::where('id',$id)->delete();
        return $result? '1' : '0';
    }
}
