<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyArticleTable extends Migration
{
    /**
     * Run the migrations.
     * 文章表
     * @return void
     */
    public function up()
    {
        Schema::create('jy_article', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->default(0)->comment('文章发布者id');
            $table->integer('cate_id')->default(0)->comment('文章分类id');
            $table->string('title',60)->default('')->comment('文章标题');
            $table->datetime('publish_at')->comment('文章发布时间');
            $table->integer('clicks')->default(rand(1,100))->comment('点击次数');
            $table->enum('status',[1,2,3])->default('1')->comment('文章状态 1待审核 2审核 3发布');
            $table->string('description')->default('')->comment('文章描述');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jy_article');
    }
}
