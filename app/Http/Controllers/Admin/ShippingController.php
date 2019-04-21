<?php

namespace App\Http\Controllers\Admin;

use App\Model\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{

    /**
     * @desc 配送方式列表
     */
    public function list()
    {

        //  实例化
        $shipping = new Shipping();

        //  返回数据
        $assign['shipping'] = $this->getDataList($shipping);

        //  返回
        return view('admin.shipping.list',$assign);

    }


    /**
     * @desc  配送方式添加
     */
    public function add()
    {

        return view('admin.shipping.add');

    }


    /**
     * @desc 执行添加
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  实例化
        $shipping = new Shipping();

        //  保存数据
        $res = $this->storeData($shipping, $params);

        //  判断是否添加成功
        if(!$res){
            return redirect()->back()->with('msg','添加配送方式失败');
        }

        //  成功返回
        return redirect('/admin/shipping/list');
    }


    /**
     * @desc  删除功能
     * @param $id
     */
    public function del($id)
    {

        //  实例化
        $shipping = new Shipping();

        //  执行删除
        $this->delData($shipping,$id);

        //  返回
        return redirect('/admin/shipping/list');

    }

}
