<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->notnull();//用户id
            $table->string('siteId')->notnull();//站点id
            $table->text('tableauIds')->nullable();//tableauid的集合
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
        Schema::dropIfExists('member_report');
    }
}
