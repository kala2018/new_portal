<?php

namespace App\Http\Controllers\Adminthree;

use App\lib\func\show;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Member;
use App\Models\Admin\RelationMember;
use App\Models\Admin\RelationReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Input;
use Session;
use Excel;

class MemberController extends Controller
{
    //列表展示
    public function index(){
        //查询数据
        $data = Member::orderBy('id','desc')->get();
        //展示视图
        return view('admin3.member.index',compact('data'));
    }

    //excel模板导出
    public function excel(){
         $cellData = [
            ['username','password','gender','email','status'],
            ['用户名','密码','性别 1男 2女','邮箱','状态 1开启 2关闭']
        ];
        Excel::create('tableau用户模板',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    //添加会员
    public function add(){
        //判断请求类型
        if(Input::method() == 'POST'){
            $data = Input::only(['username','password','gender','email']);
            $data['created_at'] = date('Y-m-d H:i:s',time());
            $data['password'] = bcrypt($data['password']);
            $data['status'] = '1';
            $status = '1';
            $error = array();
            $result = Member::where('username',$data['username'])->get()->first();
            if($result){
                $error[] = $data['username'];
                $status = '0';
            }else{
                // $data['avatar'] = "/images/th.jpg";
                $result = Member::insert($data);
            }
            $da['error'] = $error;
            $da['status'] = $status;
            return $da;
        }else{
            //展示视图
            return view('admin3.member.add');
        }
    }
    //批量添加会员
    public function adds(Request $request){

        //判断请求类型
        if(Input::method() == 'POST'){
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
                $re = Member::where('username',$val['username'])->get()->first();
                if($re){
                    $error[] = $val['username'];
                    $status = '0';
                    continue;
                }
                $val['status'] = '1';
                $val['password'] = bcrypt($val['password']);
                // $val->items->password = bcrypt($val->items->password);
                $result = Member::insert($val);
            }
            // Excel::load($cretae_path, function($reader) {
            //     $data = $reader->all()->toArray();

            // });
            unlink($cretae_path);//删除该文件
            $da['error'] = $error;
            $da['status'] = $status;
            return $da;

        }else{
            //展示视图
            return view('admin3.member.adds');
        }
    }

    //四管理员修改会员信息
    public function modify($id){
        $data = Member::where('id',$id)->get()->first();
        if(Input::method() == 'POST'){
            $post = Input::only(['password','gender','status','email']);
            $data->password = bcrypt($post['password']);
            $data->gender = $post['gender'];
            $data->status = $post['status'];
            $data->email = $post['email'];
            return $data->save() ? '1':'0';
        }else{
            return view('admin3.member.modify',compact('data'));
        }
    }
    //四修改会员信息
    public function update(Request $request){
        $data = Member::where('id',$request->id)->get()->first();
        $post = $request->all();
        if($request->same_tableau == '1'){
            $this->validate($request,[
                'username' => 'required',
                'company_name' => 'required',
                'gender' => 'required',
                'old_password' => 'required',
                'password' => 'required|confirmed:password_confirmation'
            ],[
                'old_password.required' => '原始密码不为空'
            ]);
            if(!Hash::check($request->old_password,$data['password'])){
                return show::error('原始密码错误，请重试！');
            }
            $data->password = bcrypt($post['password']);
        }else if($request->same_tableau == '2'){
            $this->validate($request,[
                'username' => 'required',
                'company_name' => 'required',
                'gender' => 'required',
            ]);
        }
        $data->gender = $post['gender'];
        $data->company_name = $post['company_name'];
        $data->username = $post['username'];
        return $data->save() ? show::success('OK'):show::error('更新失败，请重试！');
    }


    //删除会员
    public function delete(){
        $id = Input::only('id')['id'];
        $data = Member::where('id',$id)->get()->first();
        RelationReport::where('member_id',$id)->delete();
        $result = $data ->delete();
        // $data->save();
        return $result?'1':'0';
    }

    public function info()
    {
        $user = Auth::guard('member')->user();
        return view('admin3.member.info',compact('user'));
    }

    //批量删除会员
    public function deletes(){
        $ids = Input::get('ids');
        foreach($ids as $key=>$val){
            $data = Member::where('id',$val)->get()->first();
            RelationReport::where('member_id',$val)->delete();
            $result = $data ->delete();
        }
        // $data->save();
        return $result?'1':'0';
    }

    //项目组的映射
    public function report($id){
        $user = Member::where('id',$id)->get()->first();
        if(Input::method() == 'POST'){
            $project_group = Input::get('project_group');
            foreach($project_group as $key=>$value){
                if($value == null){
                    unset($project_group[$key]);
                }
            }
            $project_group = implode("|",$project_group);
            $data['member_id'] = $user->id;
            $data['project_group'] = $project_group;
            $re = Relationmember::where('member_id',$id)->get()->first();
            if($re){
                $result = $re->update($data);
            }else{
                $result = Relationmember::insert($data);
            }
            return $result ? '1':'0';
        }else{
            $da = RelationMember::where('member_id',$id)->get()->first();
            if(!$da){
                return view('admin3.report.index');
            }else{
                $project_group = explode('|',$da->project_group);

            }
            return view('admin3.report.index',compact('project_group'));
        }
    }
}
