<?php

namespace App\Http\Controllers\Admin;

use App\Model\ArticleCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleCategoryController extends Controller
{

    //  受保护的实例化
    protected $articleCategory = null;

    /**
     * @desc  实例化类
     * AdController constructor.
     */
    public function __construct()
    {

        $this->articleCategory = new ArticleCategory();

    }

    /**
     * @desc  文章分类列表页面
     */
    public function list()
    {

        //  获取分类列表数据
        $assign['list'] = $this->articleCategory->getCategoryList();

        //  返回
        return view('admin.article.category.list',$assign);

    }

    /**
     * @desc  添加页面
     */
    public function add()
    {

        return view('admin.article.category.add');

    }

    /**
     * @desc  执行添加操作
     */
    public function store(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  分类名称不能为空
        if(!isset($params['cate_name']) || empty($params['cate_name'])){
            return redirect()->back()->with('msg','分类名称不能为空');
        }

        //  删除_token
        unset($params['_token']);

        //  执行添加
        $res = $this->articleCategory->doAdd($params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','分类添加失败');
        }

        //  返回
        return redirect('/admin/article/category/list');

    }


    /**
     * @desc  分类编辑页面
     */
    public function edit($id)
    {

        //  获取分类详情
        $assign['info'] = $this->articleCategory->getInfo($id);

        //  返回
        return view('admin.article.category.edit',$assign);

    }


    /**
     * @desc  执行编辑操作
     */
    public function doEdit(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  分类名称不能为空
        if(!isset($params['cate_name']) || empty($params['cate_name'])){
            return redirect()->back()->with('msg','分类名称不能为空');
        }

        //  删除_token
        unset($params['_token']);


        //  执行编辑
        $res = $this->articleCategory->doEdit($params, $params['id']);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','分类修改失败');
        }

        //  返回
        return redirect('/admin/article/category/list');

    }

    /**
     * @desc  执行删除的操作
     */
    public function del($id)
    {

        //  执行删除
        $res = $this->articleCategory->del($id);

        //  返回
        return redirect('/admin/article/category/list');

    }


}
