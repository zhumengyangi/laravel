<?php

namespace App\Http\Controllers\Api;

use App\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{


    /**
     * @desc  添加小说
     * @param Request $request
     * @return string|void
     */
    public function add(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  成功的时候
        $return = [
            'code' => 2000,
            'msg'  => '评论成功'
        ];

        //  赋值
        $data = [
            'novel_id'   =>  $params['novel_id'],
            'user_id'    =>  isset($params['user_id']) ?? 1,
            'content'    =>  $params['content'],
            'status'     =>  1,
            'created_at' =>  date('Y-m-d H:i:s',time())

        ];

        //  实例化
        $comment= new Comment();

        //  调用该方法
        $res = $comment->addComment($data);

        //  非成功
        if(!$res){
            $return = [
                'code' => 4001,
                'msg'  => '评论失败'
            ];
        }

        return json_encode($return);

    }


    /**
     * @desc  获取评论列表
     * @param $novelId
     * @return string|void
     */
    public function list($novelId)
    {

        //  实例化
        $comment= new Comment();

        //  调用该方法
        $list = $comment->getApiList($novelId);

        //  返回
        $return = [
            'code' => 2000,
            'msg'  => '评论成功',
            'data' => $list
        ];



        return json_encode($return);

    }


    /**
     * @desc  删除评论
     * @param $id
     */
    public function del($id)
    {

        $return = [
            'code' => 2000,
            'msg'  => '删除评论成功'
        ];

        //  实例化
        $comment = new Comment();

        //  调取该方法
        $res = $comment->delRecord($id);

        if(!$res){
            $return = [
                'code' => 4002,
                'msg'  => '删除评论失败'
            ];
        }

        return json_encode($return);

    }


}
