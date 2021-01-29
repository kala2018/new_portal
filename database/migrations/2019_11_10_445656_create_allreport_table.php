<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllreportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allreport', function (Blueprint $table) {
            $table->increments('id');
            $table->string('report_name');//报表名
            $table->string('report_id');//报表id
            $table->string('workBook_id');//报表id
            $table->string('workBook_name');//报表名
            $table->string('project_name');//报表id
            $table->string('contentUrl');//报表contenturl
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allreport');
    }
}
