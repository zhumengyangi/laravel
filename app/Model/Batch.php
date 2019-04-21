<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{

    //  批次管理
    protected  $table = "jy_batch";

    //  不维护时间
    public $timestamps = false;

}
