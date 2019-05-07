<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{

    //  链接表名
    protected $table = "jy_goods";

    //  不维护时间戳
    public $timestamps = false;


    /**
     * 通过关键字获取商品信息
     *
     * @param $keywords
     * @return Goods|\Illuminate\Database\Eloquent\Builder|Model|null
     */
    public function getGoodsByKeywords($keywords)
    {

        $goodsInfo = self::select('id','goods_name','goods_sn','market_price','goods_num','goods_desc')
                         ->where('goods_name','like','%','$keywords'.'%')
                         ->orWhere('goods_sn','like','%',$keywords.'%')
                         ->first();

        return $goodsInfo;

    }

}
