<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{

    //  链接表名
    protected $table = "jy_ad";

    //  不维护时间戳
    public $timestamps = false;

    /**
     * @desc  获取广告的列表
     */
    public function getAdList()
    {

        return self::select('jy_ad.*','jy_ad_position.position_name')
            ->leftJoin('jy_ad_position','jy_ad.position_id','=','jy_ad_position.id')
            ->paginate(2);
    }

}
