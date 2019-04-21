<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{

    //  链接表名
    protected $table = "jy_article_category";

    //  不维护时间戳
    public $timestamps = false;


    /**
     * @desc  获取分类列表数据
     * @return array
     */
    public function getCategoryList()
    {

        //  以数组的形式查找出来
        return self::get()->toArray();

    }

    /**
     * @desc  获取分类详情
     * @param $id
     * @return Model|null|static
     */
    public function getInfo($id)
    {

        //  根据id 获取一条数据
        return self::where('id',$id)->first();

    }

    /**
     * @desc  执行分类添加 查到id
     * @param $data
     * @return bool
     */
    public function doAdd($data)
    {

        //  添加数据
        return self::insertGetId($data);

    }


    /**
     * @desc  执行编辑操作
     * @return array
     */
    public function doEdit($data, $id)
    {

        //  修改数据
        return self::where('id',$id)->update($data);

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
