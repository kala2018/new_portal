<?php


namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Model;

class ApplyToMember extends Model
{
    protected $table = 'apply_to_member';

    protected $fillable = ['memberId','authIds','status','created_at','updated_at'];

    public $timestamps = false;
}
