<?php

namespace App\Http\Controllers\Admin;

use App\Model\AdPosition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdPositionController extends Controller
{


    /**
     * @desc  广告位列表
     */
    public function list()
    {

        //  实例化
        $position = new AdPosition();


        //  获取广告位数据
        $assign['position'] = $position->getList();

        //  返回
        return view('admin.position.list',$assign);

    }

    /**
     * @desc  添加页面
     */
    public function add()
    {

        return view('admin.position.add');

    }

    /**
     * @desc  执行保存(添加)信息
     */
    public function store(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  实例化
        $position = new AdPosition();

        //  执行添加
        $res = $this->storeData($position, $params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','广告位添加失败');
        }

        //  返回
        return redirect('/admin/position/list');

    }


    /**
     * @desc  编辑页面
     */
    public function edit($id)
    {

        //  实例化
        $position = new AdPosition();

        //  获取数据的公共方法操作
        $assign['info'] = $this->getDataInfo($position, $id);

        //  返回
        return view('admin.position.edit',$assign);

    }


    /**
     * @desc  执行编辑操作
     */
    public function doEdit(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  查询出来的对象
        $position = AdPosition::find($params['id']);

        //  执行添加
        $res = $this->storeData($position, $params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','广告位修改失败');
        }

        //  返回
        return redirect('/admin/position/list');

    }

    /**
     * @desc  执行删除的操作
     */
    public function del($id)
    {

        //  实例化
        $position = new AdPosition();

        //  执行删除
        $res = $position->del($id);

        //  返回
        return redirect('/admin/position/list');

    }


}
