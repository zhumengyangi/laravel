<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

    //  静态属性的 状态
    const
        USE_ABLE = 1,// 可用
        USE_DISABLE = 2,//  不可用
        END = true;

    //  链接表名
    protected $table = "jy_brand";

    //  不维护时间戳
    public $timestamps = false;

    /**
     * @desc 获取品牌列表数据
     */
    public static function getList()
    {
        return self::get()->toArray();
    }

    /**
     * @desc 获取品牌的详情
     * @param $id
     * @return Model|null|static
     */
    public static function getInfo($id)
    {
        return self::where('id',$id)->first();
    }

    /**
     * @desc  添加商品品牌
     */
    public static function create($data)
    {
        return self::insert($data);
    }


    /**
     * @desc 执行修改操作
     * @param $data
     * @param $id
     * @return bool
     */
    public static function doUpdate($data, $id)
    {
        return self::where('id',$id)->update($data);
    }

    /**
     * @desc 执行删除的操作
     * @param $id
     */
    public static function del($id)
    {
        return self::where('id',$id)->delete();
    }

}
