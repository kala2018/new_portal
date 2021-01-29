<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apply_auth', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parentId')->comment('父级id')->default('0')->nullable();
            $table->integer('categoryId')->comment('分类id')->nullable();
            $table->string('name')->comment('该分类名称')->nullable();
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
        Schema::dropIfExists('apply_auth');
    }
}
