<?php

namespace App\Http\Controllers\Admin;

use App\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{


    /**
     * @desc 列表
     */
    public function list()
    {

        return view('admin.comment.list');

    }


    /**
     * @desc 获取评论的数据
     * @return string
     */
    public function getComment()
    {

        $return = [
            'code' => 2000,
            'msg'  => '成功',
        ];

        $comment = new Comment();

        $data = $comment->getLists();

//        dd($data);

        $return['data'] = [
            'total_page' => $data['last_page'],
            'page' => $data['current_page'],
            'comment' => $data['data'],
        ];

        return json_encode($return);

    }


    /**
     * @desc 审核
     * @param $id
     */
    public function check($id)
    {

        $comment = new Comment();

        $comment->checkComment($id);

        $return = [
            'code' => 2000,
            'msg'  => '成功'
        ];

        return json_encode($return);

    }

    public function del($id)
    {

        $comment = new Comment();

        $comment->delRecord($id);

        $return = [
            'code' => 2000,
            'msg'  => '成功'
        ];

        return json_encode($return);

    }

}
