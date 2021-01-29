<?php


namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{
    protected $table = 'smsconfig';

    protected $fillable = ['name','type','template_code','access_key','access_secret','sign_name'];

    public $timestamps = false;
}

