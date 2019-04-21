<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{

    //  活动表
    protected $table = "jy_activity";

    //  不维护时间字段
    public $timestamps = false;

}
