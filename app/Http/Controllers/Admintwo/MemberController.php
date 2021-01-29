<?php

namespace App\Http\Controllers\Admintwo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Member;
use Input;
use Session;

class MemberController extends Controller
{
    //列表展示
    public function index(){
        //查询数据
        $data = Member::all();
        //展示视图
        return view('admin2.member.index',compact('data'));
    }

    //添加会员
    public function add(){
        //判断请求类型
        if(Input::method() == 'POST'){
            $data = Input::only(['username','password','gender','email']);
            $data['created_at'] = date('Y-m-d H:i:s',time());
            $data['password'] = bcrypt($data['password']);
            $data['status'] = Input::get(['tableau_user']);
            $error = array();
            $status = '1';
            $result = Member::where('username',$data['username'])->get()->first();
            if($result){
                $error[] = $data['username'];
                $status = '0';
            }else{
                if(Input::get(['tableau_user']) == '1'){
                    //创建table用户
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://tableau.kalaw.top/api/3.2/sites/".Session::get('credentials')."/users",
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
                      echo "cURL Error #:" . $err;
                    } else {
                        $res = json_decode($response);
                        $data['tableau_id'] = $res->user->name;
                    }
                }
                // $data['avatar'] = "/images/th.jpg";
                $result = Member::insert($data);
            }
            $da['error'] = $error;
            $da['status'] = $status;
            return $da;
        }else{
            //展示视图
            return view('admin2.member.add');
        }
    }

    //四修改会员信息
    public function modify($id){
        $data = Member::where('id',$id)->get()->first();
        if(Input::method() == 'POST'){
            $post = Input::only(['password','gender','status','email','mobile']);
            $data->password = bcrypt($post['password']);
            $data->gender = $post['gender'];
            $data->status = $post['status'];
            $data->email = $post['email'];
            $data->mobile = $post['mobile'];
            return $data->save() ? '1':'0';
        }else{
            return view('admin2.member.modify',compact('data'));
        }    }

    //删除会员
    public function delete(){
        $id = Input::only('id')['id'];
        $data = Member::where('id',$id)->get()->first();
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/users/".$data->tableau_id,
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
        RelationReport::where('member_id',$id)->delete();
        $data ->delete();
        // $data->save();
        return '1';
    }
}
