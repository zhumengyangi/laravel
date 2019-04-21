<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{

    //  订单表
    protected $table = "jy_order_goods";

    //  不维护时间戳
    public $timestamps = false;

}
