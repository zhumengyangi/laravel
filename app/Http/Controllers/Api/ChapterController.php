<?php

namespace App\Http\Controllers\Api;

use App\Model\Chapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChapterController extends Controller
{

    /**
     * @desc 获取小说章节列表
     * @param $novelId
     * @return string
     */
    public function chapterList($novelId)
    {

        //  实例化
        $chapter = new Chapter();

        //  调用该方法
        $list = $chapter->getApiChapterList($novelId);

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '获取小说章节列表成功',
            'data' => $list
        ];

        return json_encode($return);

    }


    public function chapterInfo($id)
    {

        //  实例化
        $chapter = new Chapter();

        //  调用该方法
        $info = $chapter->getChapter($id);

        //  上一页 下一页
        $prev = $chapter->getPrevChapter($info->novel_id,$info->sort);
        $next = $chapter->getNextChapter($info->novel_id,$info->sort);

        //  没有上一页的时候
        if(empty($prev)){
            $prevChapter = 0;
        }else{
            $prevChapter = $prev->id;
        }

        //  没有下一页的时候
        if(empty($next)){
            $nextChapter = 0;
        }else{
            $nextChapter = $next->id;
        }

        $data = [
            'prev_id' => $prevChapter,
            'next_id' => $nextChapter,
            'info' => $info
        ];

        $return = [
            'code' => 2000,
            'msg'  => '获取小说章节内容成功',
            'data' => $data
        ];

        return json_encode($return);

    }

}
