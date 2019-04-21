<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Tools\ToolsAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    /**
     * @desc  商品分类页面
     */
    public function list()
    {

        return view('admin.category.list');

    }

    /**
     * @desc  获取商品分类列表接口数据
     * @param int $fid
     * @return mixed|string
     */
    public function getListData($fid=0)
    {

        //  成功返回
        $return = [
            'code' => 2000,
            'msg'  => '成功',
        ];

        //  通过fid查询子集分类
        $list = Category::getCategoryByFid($fid);

        //  返回数据
        $return['data'] = $list;

        //  返回
        return json_encode($return);

    }


    /**
     * @desc  商品分类添加页面
     */
    public function add()
    {

        //  获取分类列表数据
        $list = Category::getCategoryList();

        //  获取无限极分类树结构
        $assign['list'] = ToolsAdmin::buildTreeString($list,0,0,'f_id');

        //  返回
        return view('admin.category.add',$assign);

    }


    /**
     * @desc  执行商品分类添加的操作
     * @param Request $request
     */
    public function doAdd(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  校验
        if(!isset($params['cate_name']) || empty($params['cate_name'])){
            return redirect()->back()->with('msg','分类名称不能为空');
        }

        //  删除不用的token
        unset($params['_token']);

        //  执行添加操作
        $res = Category::doAdd($params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','分类添加失败');
        }

        //  成功跳转
        return redirect('/admin/category/list');

    }


    /**
     * @desc  商品分类编辑页面
     * @param $id
     */
    public function edit($id)
    {

        //  获取分类信息
        $assign['info'] = Category::getCateInfo($id);

        //  获取分类列表数据
        $list = Category::getCategoryList();
        //  获取无限极分类树结构
        $assign['list'] = ToolsAdmin::buildTreeString($list, 0, 0, 'f_id');

        //  返回
        return view('admin.category.edit',$assign);
    }


    /**
     * @desc  商品执行编辑
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  校验
        if(!isset($params['cate_name']) || empty($params['cate_name'])){
            return redirect()->back()->with('msg','分类名称不能为空');
        }

        //  删除不用的token
        unset($params['_token']);

        //  执行修改操作
        $res = Category::doUpdate($params, $params['id']);

        //  修改失败
        if(!$res){
            return redirect()->back()->with('msg','分类修改失败');
        }

        //  成功跳转
        return redirect('/admin/category/list');

    }

    /**
     * @desc  执行删除
     * @param $id
     */
    public function del($id)
    {

        //  删除
        $res = Category::del($id);

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
        $res = Category::doUpdate($data, $params['id']);

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
