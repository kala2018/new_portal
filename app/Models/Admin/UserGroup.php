<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    //
    protected $table = 'usergroup';

    protected $fillable = ['group_name','project_group','created_at','updated_at'];

    public $timestamps = false;
}
