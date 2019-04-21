<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    //  链接表名
    protected $table = "jy_article";


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
     * @desc  获取文章列表
     * @return array
     */
    public function getList()
    {

        //  连表查询 jy_article_category，jy_article
        return self::select('jy_article.id','jy_article_category.cate_name','title','publish_at','clicks','status')
                   ->leftJoin('jy_article_category','jy_article.cate_id','=','jy_article_category.id')
                   ->paginate(2);

        return $list;

    }

    /**
     * @desc  获取详情信息
     * @param $id
     * @return Model|null|static
     */
    public function getInfo($id)
    {

        //  根据id 获取一条数据
        return self::where('id',$id)->first();

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


    /**
     * @desc  最新文章列表
     * @param int $limit
     * @param array $where
     */
    public function getNewArticles($limit = 5, $where = [])
    {

        return self::select('id','title')
                    ->where($where)
                    ->orderBy('publish_at','desc')
                    ->limit($limit)
                    ->get()
                    ->toArray();

    }


}
