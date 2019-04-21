<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    //  链接表名
    protected $table = "jy_payment";

    //  不维护时间字段
    public $timestamps = false;

}
