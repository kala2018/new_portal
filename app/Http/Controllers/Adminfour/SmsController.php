<?php


namespace App\Http\Controllers\Adminfour;


use App\Http\Controllers\Controller;
use App\lib\func\show;
use App\Models\Admin\SmsConfig;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'template_code' => 'required',
            'access_key' => 'required',
            'access_secret' => 'required',
            'sign_name' => 'required',
        ],[
            'template_code.required' => '模版CODE不能为空',
            'access_key' => 'access_key不能为空',
            'access_secret' => 'access_secret不能为空',
            'sign_name' => ' 短信签名名称不能为空',
        ]);

        $res = SmsConfig::find($request->id);
        $data = $request->only(['name','template_code','access_key','access_secret','sign_name']);
        if(!$res){
            return show::error('配置不存在请重试！');
        }
        return $res->update($data) ? show::success('OK') : show::error('配置不存在请重试！');
    }

    public function create()
    {
        return view('admin4.sms.create');
    }
}
