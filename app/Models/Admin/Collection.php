<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    //
    protected $table = 'collection';

    protected $fillable = ['siteId','user_id','contentUrl','type','project_name','workBook_name','report_name','report_id','filter','created_at','updated_at'];

    public $timestamps = false;
}
