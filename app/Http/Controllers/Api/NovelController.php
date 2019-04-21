<?php

namespace App\Http\Controllers\Api;

use App\Model\Novel;
use App\Model\Chapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NovelController extends Controller
{


    /**
     * @desc 小说书单接口
     * @param Request $request
     * @return string
     */
    public function bookList(Request $request)
    {

        //  实例化
        $novel = new Novel();

        //  调取该方法
        $list = $novel->getLists()->toArray();

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '获取小说书单成功',
            'data' => [
                'page' => $list['current_page'],
                'total_page' => $list['last_page'],
                'list' => $list['data']
            ]
        ];

        return json_encode($return);

    }


    /**
     * @desc  获取小说的阅读榜单
     * @param Request $request
     * @return string
     */
    public function bookRank(Request $request)
    {

        //  总共显示八个
        $num = $request->input('num',8);

        //  实例化
        $novel = new Novel();

        //  调取该方法
        $list = $novel->getReadRank($num);

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '获取小说阅读榜单成功',
            'data' => $list
        ];

        return json_encode($return);

    }


    public function detail($id)
    {

        //  实例化
        $novel = new Novel();
        $chapter = new Chapter();

        //  调取该方法
        $detail = $novel->getApiNovelDetail($id);

        //  小说第一章节的id
        $first = $chapter->getFirstChapter($id);


        if(empty($first)){
            $chapterId = 0;
        }else{
            $chapterId = $first->id;
        }


        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '获取小说详情成功',
            'data' => [
                'chapter_id' => $chapterId,
                'detail' => $detail
            ]
        ];

        return json_encode($return);

    }


    /**
     * @desc 小说点击次数接口
     * @param $id
     * @return string
     */
    public function clicks($id)
    {

        //  实例化
        $novel = new Novel();

        //  调取该方法
        $novel->updateClicks($id);

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '更新点击次数成功'
        ];

        return json_encode($return);

    }


    /**
     * @desc 阅读次数更新
     * @param $id
     * @return string
     */
    public function readNum($id)
    {

        //  实例化
        $novel = new Novel();

        //  调取该方法
        $novel->updateRead($id);

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '更新阅读量成功'
        ];

        return json_encode($return);
    }

}
