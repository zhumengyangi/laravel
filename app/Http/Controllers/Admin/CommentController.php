<?php

namespace App\Http\Controllers\Admin;

use App\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{


    /**
     * @desc  商品评论列表
     */
    public function list()
    {

        //  实例化
        $comment = new Comment();

        //  获取分页数据
        $assign['comment'] = $comment->getCommentList();

        //  返回
        return view('admin.comment.list',$assign);

    }

    /**
     * @desc  删除
     * @param $id
     */
    public function del($id)
    {

        //  实例化
        $comment = new Comment();

        //  删除
        $this->delData($comment,$id);

        //  返回
        return redirect('/admin/goods/comment/list');

    }

}
