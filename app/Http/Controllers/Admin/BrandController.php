<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{

    /**
     * @desc 商品品牌列表页面
     */
    public function list()
    {

        return view('admin.brand.list');

    }


    /**
     * @desc  获取列表的数据
     * @param Request $request
     * @return mixed|string
     */
    public function getListData(Request $request)
    {

        //  获取品牌列表数据
        $list = Brand::getList();

        //  成功返回
        $return = [
            'code' => 2000,
            'msg'  => '成功',
            'data' => $list,
        ];

        //  返回
        return json_encode($return);

    }


    /**
     * @desc 商品品牌添加页面
     */
    public function add()
    {

        return view('admin.brand.add');

    }


    /**
     * @desc 执行商品品牌添加
     * @param Request $request
     */
    public function doAdd(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  判断品牌是否为空
        if(!isset($params['brand_name']) || empty($params['brand_name'])){
            return redirect()->back()->with('msg','商品品牌不能为空');
        }

        //  删除不用的token
        unset($params['_token']);

        //  添加商品品牌
        $res = Brand::create($params);

        //  判断是否正确
        if(!$res){
            return redirect()->back()->with('msg','商品品牌添加失败');
        }

        //  最后返回
        return redirect('/admin/brand/list');

    }


    /**
     * @desc  修改页面
     * @param $id
     */
    public function edit($id)
    {

        //  获取分类信息
        $assign['info'] = Brand::getInfo($id);

        //  返回
        return view('admin.brand.edit',$assign);
    }


    /**
     * @desc  执行修改
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  判断品牌是否为空
        if(!isset($params['brand_name']) || empty($params['brand_name'])){
            return redirect()->back()->with('msg','商品品牌不能为空');
        }

        //  删除不用的token
        unset($params['_token']);

        //  赋值id
        $id = $params['id'];

        //  执行修改操作
        $res = Brand::doUpdate($params, $id);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','商品品牌修改失败');
        }

        //  成功跳转
        return redirect('/admin/brand/list');

    }


    /**
     * @desc  执行删除的操作
     * @param $id
     * @return mixed|string
     */
    public function del($id)
    {

        //  删除
        $res = Brand::del($id);

        //  成功返回
        $return = [
            'code' => 2000,
            'msg'  => '成功'
        ];

        //  判断方法是否成功
        if(!$res){

            $return = [
                'code' => 4000,
                'msg'  => '删除失败'
            ];

        }

        //  返回
        return json_encode($return);

    }


    /**
     * @desc  修改商品属性
     * @param Request $request
     * @return mixed|string
     */
    public function changeAttr(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  成功
        $return = [
            'code' => 2000,
            'msg'  => '成功'
        ];

        //  组装要修改的数据值
        $data = [
            $params['key'] => $params['value'],
        ];

        //  执行修改
        $res = Brand::doUpdate($data, $params['id']);

        //  判断是否执行成功
        if(!$res){

            $return = [
                'code' => 4000,
                'msg'  => '属性修改失败'
            ];

        }

        //  返回
        return json_encode($return);

    }




}
