<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    //  连接表
    protected $table = "comment";

    //  获取评论列表
    public function getLists()
    {

        return self::select('comment.id', 'novel.name', 'user.username', 'content', 'comment.status')
                   ->leftJoin('novel', 'novel.id', '=', 'comment.novel_id')
                   ->leftJoin('user', 'user.id', '=', 'comment.user_id')
                   ->orderBy('comment.id', 'desc')
                   ->paginate(5)
                   ->toArray();

    }


    /**
     * @desc  修改审核
     * @param $id
     * @return bool
     */
    public function checkComment($id)
    {

        return self::where('id',$id)->where('status',1)->update(['status'=>2]);

    }


    /**
     * @desc 删除评论
     * @param $id
     */
    public function delRecord($id)
    {

        return self::where('id',$id)->delete();

    }


    /**
     * @desc  添加评论
     * @param $data
     * @return bool
     */
    public function addComment($data)
    {

        return self::insert($data);

    }


    /**
     * @desc 评论列表
     * @param $novelId
     * @return array
     */
    public function getApiList($novelId)
    {

        return self::select('comment.id','user.username','content','comment.created_at')
                    ->leftJoin('user','user.id','=','comment.user_id')
                    ->where('comment.novel_id',$novelId)
                    ->orderBy('comment.id','desc')
                    ->get()
                    ->toArray();

    }

}
