<?php


namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Model;

class ApplyAuth extends Model
{
    protected $table = 'apply_auth';

    protected $fillable = ['parentId','caregoryId','name','created_at','updated_at'];

    public $timestamps = false;
}
