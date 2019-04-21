<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{

    //  链接表名
    protected $table = "author";

    const PAGE_SIZE = 5;


    /**
     * @desc 作者分页显示
     */
    public function getLists()
    {
        return self::paginate(self::PAGE_SIZE);
    }


    /**
     * @desc 执行作者添加
     * @param $data
     */
    public function addRecord($data)
    {
        return self::insert($data);
    }


    /**
     * @desc 删除作者
     * @param $id
     */
    public function delRecord($id)
    {
        return self::where('id',$id)->delete();
    }


    /**
     * @desc 获取作者列表
     * @return array
     */
    public function getAuthor()
    {
        return self::get()->toArray();
    }

}
