<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    //  地区表
    protected $table = "jy_region";

    //  不维护时间字段
    public $timestamps = false;

}
