<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsconfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smsconfig', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('type')->default('1')->comment("1为阿里云短信配置");
            $table->string('template_code')->nullable();//模板CODE
            $table->string('access_key')->nullable();
            $table->string('access_secret')->nullable();
            $table->string('sign_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smsconfig');
    }
}
