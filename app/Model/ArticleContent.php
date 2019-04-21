<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArticleContent extends Model
{


    //  链接表名
    protected $table = "jy_article_content";

    //  不维护时间戳
    public $timestamps = false;


    /**
     * @desc  执行分类添加 查到id
     * @param $data
     * @return bool
     */
    public function doAdd($data)
    {

        //  添加数据
        return self::insert($data);

    }

    /**
     * @desc  执行编辑操作
     * @return array
     */
    public function doEdit($data, $aid)
    {

        //  修改数据
        return self::where('a_id',$aid)->update($data);

    }



    /**
     * @desc  获取内容详情
     * @param $id
     * @return Model|null|static
     */
    public function getInfo($id)
    {

        //  根据a_id 获取一条数据
        return self::where('a_id',$id)->first();

    }


    /**
     * @desc  执行删除的操作
     * @param $id
     * @return bool|null
     */
    public function del($id)
    {

        //  根据id 删除数据
        return self::where('a_id',$id)->delete();

    }


}
