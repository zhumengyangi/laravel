<?php

namespace App\Http\Controllers\Admin;

use App\Model\GoodsAttr;
use App\Model\GoodsType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsTypeController extends Controller
{


    //  受保护的实例化
    protected $type = null;
    protected $attr = null;


    /**
     * @desc  实例化类
     * AdController constructor.
     */
    public function __construct()
    {
        $this->type = new GoodsType();
        $this->attr  = new GoodsAttr();
    }

    /**
     * @desc  商品类型列表页面
     */
    public function list()
    {

        //  获取没有分页的数据列表
        $assign['list'] = $this->getDataList($this->type);

        //  返回
        return view('admin.goodsType.list',$assign);

    }


    /**
     * @desc  商品类型添加页面
     */
    public function add()
    {

        //  返回
        return view('admin.goodsType.add');

    }


    /**
     * @desc  执行商品类型添加的操作
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  删除不用的token
        $params = $this->delToken($params);

        //  保存数据
        $res = $this->storeData($this->type, $params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','商品类型添加失败');
        }

        //  成功跳转
        return redirect('/admin/goods/type/list');

    }


    /**
     * @desc  商品类型编辑页面
     * @param $id
     */
    public function edit($id)
    {

        //  通过id获取该数据（默认为主键id）
        $assign['info'] = $this->getDataInfo($this->type, $id);

        //  返回
        return view('admin.goodsType.edit',$assign);

    }



    /**
     * @desc  商品类型执行编辑
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  删除不用的token
        $params = $this->delToken($params);

        //  根据id查到该条数据
        $type = GoodsType::find($params['id']);

        //  保存数据（编辑）
        $res = $this->storeData($type, $params);

        //  编辑失败
        if(!$res){
            return redirect()->back()->with('msg','商品类型编辑失败');
        }

        //  成功跳转
        return redirect('/admin/goods/type/list');

    }

    /**
     * @desc  执行删除
     * @param $id
     */
    public function del($id)
    {

        try{

            //  开始事物
            DB::beginTransaction();

            //  删除商品类型
            $this->delData($this->type, $id);
            //  删除商品类型的属性
            $this->delData($this->attr, $id, 'cate_id');

            //  写日志
            \Log::info('商品类型删除成功');

            //  事物提交
            DB::commit();

        }catch (\Exception $e){

            //  事物回滚
            DB::rollBack();

            //  写日志
            \Log::info('商品类型删除失败'.$e->getMessage());

        }

        //  成功跳转
        return redirect('/admin/goods/type/list');

    }

}
