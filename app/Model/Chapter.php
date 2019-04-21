<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{

    //  连接表明
    protected $table = "chapter";


    /**
     * @desc  小说章节添加
     * @param $data
     */
    public function addRecord($data)
    {

        return self::insert($data);

    }


    /**
     * @desc 获取小说章节列表
     * @param int $noveId 小说Id
     */
    public function getLists($novelId = 0)
    {

        //  判断是否有小说Id传过来
        if ($novelId == 0) {//  没有就调到所有的章节列表

            return self::select('chapter.id', 'novel.name', 'chapter.title', 'chapter.sort')
                       ->leftJoin('novel', 'chapter.novel_id', '=', 'novel.id')
                       ->orderBy('id','desc')
                       ->paginate(5);

        } else {//  有 跳到对应的小说章节列表

            return self::select('chapter.id', 'novel.name', 'chapter.title', 'chapter.sort')
                       ->leftJoin('novel', 'chapter.novel_id', '=', 'novel.id')
                       ->where('novel_id', $novelId)
                       ->orderBy('id','desc')
                       ->paginate(5);

        }
    }


    /**
     * @desc  删除章节
     * @param $id
     */
    public function delRecord($id)
    {

        return self::where('id', $id)->delete();

    }


    /**
     * @desc  获取章节信息
     * @param $id
     */
    public function getChapter($id)
    {

        return self::where('id', $id)->first();

    }


    /**
     * @desc  修改章节的记录
     * @param $id
     * @param $data
     */
    public function editRecord($data, $id)
    {

        return self::where('id', $id)->update($data);

    }


    /**
     * @desc 小说详情接口
     * @param $novelId
     */
    public function getApiChapterList($novelId)
    {

        $list = self::select('id','novel_id','title','sort')
                    ->where('novel_id',$novelId)
                    ->orderBy('sort')
                    ->get()
                    ->toArray();

        return $list;

    }


    /**
     * @desc  获取小说的第一章节
     * @param $novelId
     * @return Model|null|object|static
     */
    public function getFirstChapter($novelId)
    {

        $first = self::where('novel_id',$novelId)
                     ->first();

        return $first;

    }


    /**
     * @desc 获取小说上一上内容
     * @param $novelId 小说id
     * @param $sort 章节号
     */
    public function getPrevChapter($novelId, $sort)
    {

        $prev = self::where('novel_id',$novelId)
                    ->where('sort',$sort-1)
                    ->first();

        return $prev;

    }


    /**
     * @desc 获取小说下一章内容
     * @param $novelId 小说id
     * @param $sort    章节号
     */
    public function getNextChapter($novelId, $sort)
    {

        $prev = self::where('novel_id',$novelId)
                    ->where('sort',$sort+1)
                    ->first();

        return $prev;

    }


}
