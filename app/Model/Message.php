<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    //制定数据表的名字
    protected $table = "jy_message";

    //  维护时间戳
    public $timestamps = true;

}
