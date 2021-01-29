<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('user_id');//收藏者id
            $table->enum('type',[1,2]);//报表所属的是管理员还是用户，1代表管理员，2代表用户
            $table->string('project_name');//项目名
            $table->string('workBook_name');//工作簿名
            $table->string('report_name');//报表名
            $table->string('report_id');//报表id
            $table->string('contentUrl');//报表contenturl
            $table->string('filter');//报表的filter
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
        Schema::dropIfExists('collection');
    }
}
