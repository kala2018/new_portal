<?php

namespace App\Http\Controllers\Adminthree;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use DB;
use Session;
use App\Models\Admin\System;

class SystemController extends Controller
{
    public function update(Request $request){
        if(Input::method() == 'POST'){
            //系统设置的修改
            $default = System::get()->first();
            if($request->same_tableau == '1'){
                $default->tableau_username = $request->tableau_username;
                $default->tableau_password = $request->tableau_password;
            }
            $tableau_domain = Input::only("tableau_domain")["tableau_domain"];
            $web_title = Input::only('web_title')['web_title'];
            $company = Input::only('company')['company'];
            $toolbar = Input::only('toolbar')['toolbar'];
            $model = Input::only('model')['model'];
            $file = $request->file('logo_img');
            $back = $request->file('background_url');
            if($file){

                $allowed_extensions = ["png", "jpg", "gif","PNG",'jpeg'];
                if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                    return ['error' => 'You may only upload png, jpg , PNG , jpeg or gif.'];
                }
                $destinationPath = 'images/'; //public 文件夹下面建 imges 文件夹

                $extension = $file->getClientOriginalExtension();
                $fileName = str_random(10).'.'.$extension;
                $file->move($destinationPath, $fileName);
                $filePath = asset($destinationPath.$fileName);
                $default->logo_url = '/'.$destinationPath.$fileName;
            }
            if($back){

                $alloweds = ["png", "jpg", "gif","PNG",'jpeg'];
                if ($back->getClientOriginalExtension() && !in_array($back->getClientOriginalExtension(), $alloweds)) {
                    return ['error' => 'You may only upload png, jpg , PNG , jpeg or gif.'];
                }
                $destinationPat = 'background/'; //public 文件夹下面建 imges 文件夹

                $extensio = $back->getClientOriginalExtension();
                $fileNam = str_random(10).'.'.$extensio;
                $back->move($destinationPat, $fileNam);
                $filePat = asset($destinationPat.$fileNam);
                $default->background_url = '/'.$destinationPat.$fileNam;
            }
            // $post['type'] = '1';

            // $default -> type = '0';
            // $default->save();
            $default->system_domain = $tableau_domain;
            $default->web_title = $web_title;
            $default->company = $company;
            $default->toolbar = $toolbar;
            $default->model = $model;
            return $default->save() ? '1':'0';
            // //修改config配置
            // $data =  System::get()->first();
            // $data->system_domain = $tableau_domain;
            // $data -> save();
            // return 1;
        }else{
            $default = System::get()->first();
                        // dd($tableau_domain);
            return view('admin3.system.index',compact('default'));
        }
    }
}
