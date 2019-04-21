<?php

namespace App\Http\Controllers\Admin;

use App\Model\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{

    /**
     * @desc  下发页面
     */
    public function list()
    {

        //  实例化
        $message = new Message();

        //  获取数据
        $assign['message'] = $this->getPageList($message);

        //  返回
        return view('admin.message.list',$assign);

    }



    /**
     * @desc  删除活动
     * @param $id
     */
    public function del($id)
    {

        //  实例化
        $message = new Message();

        //  执行删除
        $res = $this->delData($message, $id);

        //  返回
        return redirect('/admin/message/list');

    }

}
