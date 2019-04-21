<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    //  静态属性的 状态
    const
        USE_ABLE = 1,// 可用
        USE_DISABLE = 2,//  不可用
        END = true;

    //  链接表名
    protected $table = "jy_category";

    //  不维护时间戳
    public $timestamps = false;


    /**
     * @desc  获取分类列表数据
     * @return array
     */
    public static function getCategoryList()
    {

        $list = self::get()->toArray();

        return $list;

    }

    /**
     * @desc  通过fid查询子集分类
     * @param int $fid
     */
    public static function getCategoryByFid($fid=0)
    {

        $list = self::where('f_id',$fid)->get()->toArray();

        return $list;

    }

    /**
     * @desc 获取分类的信息
     * @param $id
     */
    public static function getCateInfo($id)
    {

        return self::where('id',$id)->first();

    }

    /**
     * @desc 添加分类的数据
     * @param $data
     * @return bool
     */
    public static function doAdd($data)
    {

        return self::insert($data);

    }


    /**
     * @desc  执行修改编辑
     * @param $data
     * @param $id
     * @return bool
     */
    public static function doUpdate($data,$id)
    {

        return self::where('id',$id)->update($data);

    }


    /**
     * @desc  执行删除的操作
     * @param $id
     * @return bool|null
     */
    public static function del($id)
    {

        return self::where('id',$id)->delete();

    }


}
