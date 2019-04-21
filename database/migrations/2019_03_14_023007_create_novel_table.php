<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNovelTable extends Migration
{
    /**
     * Run the migrations.
     * @desc 小说主表
     * @return void
     */
    public function up()
    {
        Schema::create('novel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('c_id')->comment('小说类型Id');
            $table->integer('a_id')->comment('作者Id');
            $table->string('name',80)->default('')->comment('小说名字');
            $table->string('image_url',120)->default('')->comment('小说封面');
            $table->string('tags',60)->default('')->comment('小说标签');
            $table->string('desc',200)->comment('小说简介');
            $table->enum('status',['1','2'])->comment('小说状态 1连载 2完结');
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
        Schema::dropIfExists('noval');
    }
}
