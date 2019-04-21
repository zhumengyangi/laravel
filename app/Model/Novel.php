<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Novel extends Model
{

    //链接数据表
    protected $table = "novel";

    /**
     * @desc 获取列表
     * @return mixed
     */
    public function getLists()
    {

        return self::select('novel.id','c_name','author_name','c_id','a_id','name','image_url','status','clicks','tags')
                   ->join('category','novel.c_id','=','category.id')//  连接分类表
                   ->join('author','novel.a_id','=','author.id')
                   ->orderBy('novel.id','desc')
                   ->paginate(4);
    }


    /**
     * @desc 小说添加
     * @param $data
     * @return bool
     */
    public function addRecord($data)
    {
        return self::insert($data);
    }

    /**
     * @desc 小说删除
     * @param $id
     */
    public function delRecord($id)
    {
        return self::where('id',$id)->delete();
    }


    /**
     * @desc 获取小说详情
     * @param $id
     */
    public function getNovelInfo($id)
    {
        return self::where('id',$id)->first();
    }


    /**
     * @desc 小说编辑
     * @param $id
     */
    public function editRecord($data, $id)
    {
        return self::where('id',$id)->update($data);
    }


    /**
     * @desc 获取小程序首页的banner图
     * @param int $num
     * @return array
     */
    public function getBanners($num = 3)
    {

        $list = self::select('id','image_url')
                     ->orderBy('id','desc')
                     ->limit($num)
                     ->get()
                     ->toArray();

        return $list;

    }


    /**
     * @desc 获取最新小说
     * @param int $num
     * @return array
     */
    public function getNews($num = 3)
    {

        $list = self::select('novel.id','name','image_url','author_name','tags','desc','clicks')
                     ->leftJoin('author','novel.a_id','=','author.id')
                     ->orderBy('novel.id','desc')
                     ->limit($num)
                     ->get()
                     ->toArray();

        return $list;

    }


    /**
     * @desc 获取首页点击排行
     * @param int $num
     * @return array
     */
    public function getClicks($num = 3)
    {

        $list = self::select('novel.id','name','image_url','author_name','tags','desc','clicks')
                    ->leftJoin('author','novel.a_id','=','author.id')
                    ->orderBy('novel.clicks','desc')
                    ->limit($num)
                    ->get()
                    ->toArray();

        return $list;

    }


    /**
     * @desc 通过分类id查询小说列表
     * @param int $num
     * @return array
     */
    public function getNovelByCid($cid)
    {

        $list = self::select('novel.id','name','image_url','author_name','tags','status','clicks')
                    ->leftJoin('author','novel.a_id','=','author.id')
                    ->where('novel.c_id',$cid)
                    ->orderBy('id','desc')
                    ->get()
                    ->toArray();

        return $list;

    }


    /**
     * @desc 通过小说名字或者作者搜索小说列表
     * @param $name
     * @return array
     */
    public function getNovelByName($name)
    {

        $list = self::select('novel.id','name','image_url','author_name','tags','status','clicks')
                    ->leftJoin('author','novel.a_id','=','author.id')
                    ->where('novel.name','like','%'.$name.'%')
                    ->orwhere('author_name',$name)
                    ->orderBy('id','desc')
                    ->get()
                    ->toArray();

        return $list;

    }


    /**
     * @desc 获取阅读排行
     * @param $name
     * @return array
     */
    public function getReadRank($num = 8)
    {

        $list = self::select('novel.id','name','read_num')
                    ->leftJoin('author','novel.a_id','=','author.id')
                    ->orderBy('novel.read_num','desc')
                    ->limit($num)
                    ->get()
                    ->toArray();

        return $list;

    }


    /**
     * @desc 小说详情接口
     * @param $id
     */
    public function getApiNovelDetail($id)
    {

        $detail = self::select('novel.id','name','image_url','read_num','status','author_name','c_name','desc','tags')
                      ->leftJoin('author','a_id','=','author.id')
                      ->leftJoin('category','c_id','=','category.id')
                      ->where('novel.id',$id)
                      ->first();

        return $detail;

    }



    /**
     * @desc 点击量接口
     * @param $id
     */
    public function updateClicks($id)
    {

        $res = self::where('id',$id)
                   ->update(['clicks' => DB::raw('clicks+1')]);

        return $res;

    }

    /**
     * @desc 阅读量接口
     * @param $id
     */
    public function updateRead($id)
    {

        $res = self::where('id',$id)
            ->update(['read_num' => DB::raw('read_num+1')]);

        return $res;

    }


}
