<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AllReport extends Model
{
    //
    protected $table = 'allreport';

    protected $fillable = ['siteId','report_name','report_id','contentUrl','project_name','workBook_name','workBook_id'];

    public $timestamps = false;
}
