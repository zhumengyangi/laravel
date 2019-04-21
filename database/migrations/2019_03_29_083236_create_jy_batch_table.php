<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jy_batch', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_path',80)->default('')->comment('批次文件的路径地址');
            $table->enum('type',[1,2,3])->default('1')->comment('1发红包 2发短信 3发邮件');
            $table->string('content',80)->default('')->comment('批次文件的内容');
            $table->enum('status',[1,2,3])->default('1')->comment('1未审核 2待发送 3已发送');
            $table->string('note',20)->default('')->comment('备注信息');
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
        Schema::dropIfExists('jy_batch');
    }
}
