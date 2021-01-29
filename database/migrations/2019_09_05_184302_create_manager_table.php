<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager', function (Blueprint $table) {
            $table->increments('id');//主键字段
            $table->string('username',20)->notnull();//用户名
            $table->string('password')->notnull();//密码
            $table->timestamps();//创建时间和更新时间
            $table->rememberToken();//记住登陆状态的额字段
            $table->enum('status',[1,2])->notnull()->default('1');//账号的状态，1=正常，2=禁用
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager');
    }
}
