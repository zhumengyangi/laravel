<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    //  订单表
    protected $table = "jy_order";

    //  维护时间戳
    public $timestamps = true;

}
