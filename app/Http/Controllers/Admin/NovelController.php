<?php

namespace App\Http\Controllers\Admin;

use App\Tools\ToolsAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Novel;
use App\Model\Author;

class NovelController extends Controller
{

    /**
     * @desc 小说列表
     */
    public function list()
    {

        $novel = new Novel();

        //  从Model中获取
        $assign['novels'] = $novel->getLists();

        //  返回给前台数据
        return view('admin.novel.list', $assign);

    }


    /**
     * 小说添加页面
     */
    public function create()
    {
        $category = new Category();

        $author= new Author();

        //  获取分类的列表
        $assign['c_list'] = $category->getCategory();

        //  获取作者列表
        $assign['a_list'] = $author->getAuthor();

        return view('admin.novel.create',$assign);

    }


    /**
     * @desc 小说分类添加页面
     * @param Request $request
     * @return
     */
    public function store(Request $request)
    {

        //  获取所有的参数
        $params = $request->all();

        //  上传图片
        $params['image_url'] = ToolsAdmin::uploadFile($params['image_url']);

        //  删除token Key
        unset($params['_token']);

        //  实例化Model
        $novel = new Novel();

        //  添加
        $res = $novel->addRecord($params);

        //  判断是否添加成功
        if(!$res){//  否，跳回原来的页面
            return redirect()->back();
        }

        //  是 调到列表页面
        return redirect('/admin/novel/list');

    }


    //  小说删除操作
    public function del($id)
    {

        $novel = new Novel();

        $novel->delRecord($id);

        return redirect('/admin/novel/list');

    }


    /**
     * 小说编辑页面
     */
    public function edit($id)
    {
        $category = new Category();
        $author= new Author();
        $novel= new Novel();

        //  获取分类的列表
        $assign['c_list'] = $category->getCategory();

        //  获取作者列表
        $assign['a_list'] = $author->getAuthor();

        //  获取小说详情列表
        $assign['novel'] = $novel->getNovelInfo($id);

        return view('admin.novel.edit',$assign);

    }


    /**
     * @desc 执行小说编辑功能
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取所有值
        $params = $request->all();

        //  如果上传图片的话
        if(isset($params['image_url'])){

            $params['image_url'] = ToolsAdmin::uploadFile($params['image_url']);

        }

        //  小说主键Id
        $id = $params['id'];

        //  删除没用的
        unset($params['_token']);
        unset($params['id']);

        //  实例化novel
        $novel = new Novel();

        //  执行修改
        $res = $novel->editRecord($params, $id);

        //  判断如果没修改成功 跳转至之前的页面
        if(!$res){
            return redirect()->back();
        }

        //  成功跳转列表
        return redirect('/admin/novel/list');

    }

}
