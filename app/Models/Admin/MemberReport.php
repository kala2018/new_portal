<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;


class MemberReport extends Model
{
    protected $table = 'member_report';
    public $timestamps =false;
    //使用trait，就相当于将trait代码段复制到这个位置
    protected $fillable =['member_id','siteId','tableauIds','created_at','updated_at'];

}
