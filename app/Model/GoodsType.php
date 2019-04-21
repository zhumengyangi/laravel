<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{

    //  静态属性的 状态
    const
        USE_ABLE = 1,// 可用
        USE_DISABLE = 2,//  不可用
        END = true;

    //  链接表名
    protected $table = "jy_goods_type";

    //  不维护时间戳
    public $timestamps = false;

}
