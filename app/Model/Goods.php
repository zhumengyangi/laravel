<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{

    //  链接表名
    protected $table = "jy_goods";

    //  不维护时间戳
    public $timestamps = false;

}
