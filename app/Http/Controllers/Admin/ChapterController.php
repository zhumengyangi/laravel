<?php

namespace App\Http\Controllers\Admin;

use App\Model\Chapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChapterController extends Controller
{

    /**
     * @desc  小说章节添加页面
     * @param $id 小说Id
     */
    public function create($id)
    {

        $assign['novel_id'] = $id;

        return view('admin.chapter.create', $assign);

    }


    /**
     * @desc  保存章节
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取所有的参数
        $params = $request->all();

        //  实例化
        $chapter = new Chapter();

        //  删除不用的token
        unset($params['_token']);

        //  添加
        $res = $chapter->addRecord($params);

        //  判断是否添加成功
        if (!$res) {
            return redirect('/admin/chapter/add/' + $params['novel_id']);
        }

        return redirect('/admin/chapter/list');

    }

    /**
     * @desc  章节列表
     * @param int $novelId
     */
    public function list($novelId = 0)
    {
        //  实例化
        $chapter = new Chapter();

        //  传或不传小说Id 跳到小说列表
        $assign['chapter_list'] = $chapter->getLists($novelId);

        //  返回
        return view('admin.chapter.list', $assign);

    }


    /*
     * @desc 编辑页面
     */
    public function edit($id)
    {

        //  实例化
        $chapter = new Chapter();

        //  传Id给编辑哪个
        $assign['chapter'] = $chapter->getChapter($id);

        //  返回
        return view('admin.chapter.edit', $assign);

    }


    /**
     * @desc 执行编辑
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取所有数据
        $params = $request->all();

        //  实例化
        $chapter = new Chapter();

        //  获取主键Id
        $id = $params['id'];

        //  删除token
        unset($params['_token']);

        //  传参修改
        $res = $chapter->editRecord($params, $id);

        //  修改失败返回该小说章节页面
        if(!$res){
            return redirect('/admin/chapter/edit/'+$params['novel_id']);
        }

        return redirect('/admin/chapter/list/'.$params['novel_id']);

    }


    /**
     * @desc 章节删除
     * @param $id
     */
    public function del($id)
    {

        //  实例化
        $chapter = new Chapter();

        //  执行删除
        $chapter->delRecord($id);

        //  返回
        return redirect('/admin/chapter/list');

    }

}
