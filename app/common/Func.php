<?php


namespace App\common;


use App\Models\Admin\ApplyAuth;
use Illuminate\Support\Facades\Storage;

class Func
{
    public static function setImage($path,$data)
    {
        Storage::disk('public')->put($path.'png',$data);
        return $path.'.png';
    }

    public static  function getimgsuffix($name) {
        $info = getimagesize($name);
        $suffix = false;
        if($mime = $info['mime']){
            $suffix = explode('/',$mime)[1];
        }
        return $suffix;
    }

    public static function createFile($path)
    {
        /* 新建文件夹 */
        $dirname = str_replace(strrchr(basename($path), "."), "", $path);
        $dir = substr($dirname, 0, strripos($dirname, '/'));
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }


    public static function categoryData($data)
    {
        $res = [];
        $tree = [];
        foreach ($data as $k => $v){
            $v['list'] = [];
            $res[$v['id']] = $v;
        }
        foreach ($res as $ks => $vs){
            if($res[$ks]['parentId'] != 0){
                $res[$res[$ks]['parentId']]['list'][] = &$res[$ks];
            }else{
                $tree[] = &$res[$ks];
            }
        }
        return $tree;
    }
}
