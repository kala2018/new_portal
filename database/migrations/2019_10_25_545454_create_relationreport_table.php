<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationreportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationreport', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('member_id');
            $table->string('project_name');//项目名
            $table->string('workBook_name');//工作簿名
            $table->string('report_name');//报表名
            $table->string('report_id');//报表id
            $table->text('project_group');//拥有的工作组
            $table->Integer('usergroup_id');//属于用户组的id
            $table->timestamps();//创建时间和更新时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationreport');
    }
}
