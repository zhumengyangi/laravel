<?php

namespace App\Http\Controllers\Admin;

use App\Model\GoodsAttr;
use App\Model\GoodsType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsAttrController extends Controller
{


    /**
     * @desc  商品属性列表页面
     */
    public function list($typeId)
    {

        //  实例化
        $attr = new GoodsAttr();

        //  根据cate_id(分类id) 来查询
        $where['cate_id'] = $typeId;

        //  获取属性列表
        $assign['attr_list'] = $attr->getList($where);

        //  返回
        return view('admin.goodsAttr.list',$assign);

    }


    /**
     * @desc  商品属性添加页面
     */
    public function add()
    {

        //  实例化
        $type = new GoodsType();

        //  获取没有分页的数据列表
        $assign['type_list'] = $this->getDataList($type);

        //  返回
        return view('admin.goodsAttr.add',$assign);

    }


    /**
     * @desc  执行商品属性添加的操作
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  校验
        if(!isset($params['attr_name']) || empty($params['attr_name'])){
            return redirect()->back()->with('msg','属性名称不能为空');
        }

        //  删除不用的token
        $params = $this->delToken($params);

        //  实例化
        $attr = new GoodsAttr();

        //  保存数据
        $res = $this->storeData($attr, $params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','商品属性添加失败');
        }

        //  成功跳转
        return redirect('/admin/goods/attr/list/'.$params['cate_id']);

    }


    /**
     * @desc  商品属性编辑页面
     * @param $id
     */
    public function edit($id)
    {

        //  实例化
        $type = new GoodsType();

        //  获取没有分页的数据列表
        $assign['type_list'] = $this->getDataList($type);

        //  实例化
        $attr = new GoodsAttr();

        //  通过id获取该数据（默认为主键id）
        $assign['info'] = $this->getDataInfo($attr, $id);

        //  返回
        return view('admin.goodsAttr.edit',$assign);

    }



    /**
     * @desc  商品属性执行编辑
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  校验
        if(!isset($params['attr_name']) || empty($params['attr_name'])){
            return redirect()->back()->with('msg','属性名称不能为空');
        }

        //  删除不用的token
        $params = $this->delToken($params);

        //  根据id查到该条数据
        $attr = GoodsAttr::find($params['id']);

        //  保存数据（编辑）
        $res = $this->storeData($attr, $params);

        //  编辑失败
        if(!$res){
            return redirect()->back()->with('msg','商品属性编辑失败');
        }

        //  成功跳转
        return redirect('/admin/goods/attr/list/'.$params['cate_id']);

    }

    /**
     * @desc  执行删除
     * @param $id
     */
    public function del($id)
    {

        //  实例化
        $attr = new GoodsAttr();

        //  通过id获取该数据 用来返回到当前页面
        $data = $this->getDataInfo($attr, $id);

        //  执行删除
        $this->delData($attr, $id);

        //  成功跳转
        return redirect('/admin/goods/attr/list/'.$data->cate_id);

    }


}
