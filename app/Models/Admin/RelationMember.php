<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
//引入trait
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class RelationMember extends Model implements AuthenticatableContract
{
    protected $table = 'relationmember';
    public $timestamps =false;
    //使用trait，就相当于将trait代码段复制到这个位置
    use Authenticatable;

    protected $fillable =['member_id','project_group'];

    public function member(){
        return $this->hasOne('App\Models\Admin\Member','id','member_id');
    }
}
