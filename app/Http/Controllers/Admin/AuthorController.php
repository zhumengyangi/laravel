<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Author;

class AuthorController extends Controller
{

    /**
     * @desc 作者列表
     */
    public function list()
    {

        $author = new Author();

        //  从Model中获取
        $assign['authors'] = $author->getLists();

        //  返回给前台数据
        return view('admin.author.list', $assign);

    }


    /**
     * 作者添加页面
     */
    public function create()
    {
        return view('admin.author.create');
    }


    /**
     * @desc 执行作者添加页面
     * @param Request $request
     * @return
     */
    public function store(Request $request)
    {

        //  获取所有的参数
        $params = $request->all();

        //  实例化Model
        $author = new Author();

        //  赋值
        $data = [
            'author_name' => $params['author_name'] ?? "",
            'author_desc' => $params['author_desc'] ?? ""
        ];

        //  添加
        $res = $author->addRecord($data);

        //  判断是否添加成功
        if(!$res){//  否，跳回原来的页面
            return redirect()->back();
        }

        //  是 调到列表页面
        return redirect('/admin/author/list');

        return view('admin.author.create');
    }


    //  小说删除操作
    public function del($id)
    {

        $author = new Author();

        $author->delRecord($id);

        return redirect('/admin/author/list');
    }


}
