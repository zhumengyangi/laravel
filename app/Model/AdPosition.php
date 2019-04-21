<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdPosition extends Model
{

    //  链接表名
    protected $table = "jy_ad_position";

    //  不维护时间戳
    public $timestamps = false;


    /**
     * @desc  执行添加的操作
     * @param $data
     * @return bool
     */
    public function doAdd($data)
    {

        //  添加数据
        return self::insert($data);

    }


    /**
     * @desc  获取广告位列表
     * @return array
     */
    public function getList()
    {

        //  以数组的形式查找出来
        return self::get()->toArray();

    }


    /**
     * @desc  执行删除的操作
     * @param $id
     * @return bool|null
     */
    public function del($id)
    {

        //  根据id 删除数据
        return self::where('id',$id)->delete();

    }


}
