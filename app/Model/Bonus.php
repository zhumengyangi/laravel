<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{

    //  红包记录
    protected  $table = "jy_bonus";

    //  不维护时间
    public $timestamps = false;

}
