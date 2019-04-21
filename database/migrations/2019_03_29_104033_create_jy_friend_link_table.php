<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyFriendLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jy_firend_link', function (Blueprint $table) {
            $table->increments('id')->comment('友情链接表');
            $table->string('link_name',20)->default('')->comment('链接的名字');
            $table->string('url',80)->default('#')->comment('链接地址');
            $table->enum('status',[1,2])->default('1')->comment('1可用 2不可用');
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
        Schema::dropIfExists('jy_firend_link');
    }
}
