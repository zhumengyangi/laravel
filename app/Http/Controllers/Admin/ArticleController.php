<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ArticleCategory;
use App\Model\Article;
use App\Model\ArticleContent;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{

    //  受保护的实例化
    protected $category = null;
    protected $article = null;
    protected $content = null;


    /**
     * @desc  实例化类
     * AdController constructor.
     */
    public function __construct()
    {
        $this->category = new ArticleCategory();
        $this->article  = new Article();
        $this->content = new ArticleContent();
    }


    /**
     * @desc  文章列表页面
     */
    public function list()
    {

        //  获取文章列表
        $assign['list'] = $this->article->getList();

        //  返回
        return view('admin.article.article.list',$assign);

    }


    /**
     * @desc  文章添加页面
     */
    public function add()
    {

        //  获取分类列表数据
        $assign['category'] = $this->category->getCategoryList();

        //  返回
        return view("admin.article.article.add",$assign);

    }


    /**
     * @desc  执行文章添加的操作
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  删除_token
        unset($params['_token']);

        //  拿到内容并删除
        $content = $params['content'];
        unset($params['content']);

        try{
            //  开始事务
            DB::beginTransaction();

            //  执行文章的添加
            $id = $this->article->doAdd($params);

            //  执行文章内容添加
            //  赋值
            $data = [
                'a_id' => $id,
                'content' => $content
            ];

            //  执行添加
            $this->content->doAdd($data);

            //  提交事务
            DB::commit();
        }catch(\Exception $e){

            //  事务回滚
            DB::rollback();

            //  日志
            \Log::info('文章添加失败'.$e->getMessage());

            //  返回
            return redirect()->back()->with('msg',$e->getMessage());
        }

        //  返回
        return redirect('/admin/article/list');

    }



    /**
     * @desc 编辑页面
     * @param $id
     */
    public function edit($id)
    {

        //  获取分类列表数据
        $assign['category'] = $this->category->getCategoryList();

        //  获取详情信息
        $assign['info']  = $this->article->getInfo($id);

        //  获取内容详情
        $assign['content'] = $this->content->getInfo($id);

        //  返回
        return view('admin.article.article.edit', $assign);

    }

    /**
     * @desc  执行编辑操作
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  删除_token
        unset($params['_token']);

        //  赋值内容并删除
        $content = $params['content'];
        unset($params['content']);

        try{
            //  开始事务
            DB::beginTransaction();

            //  执行文章的添加
            $id = $this->article->doEdit($params,$params['id']);

            //  执行文章内容添加
            //  赋值
            $data = [
                'content' => $content
            ];

            //  执行修改
            $this->content->doEdit($data,$params['id']);

            //  写日志
            \Log::info('文章修改成功');

            //  提交事务
            DB::commit();
        }catch(\Exception $e){

            //  事物回滚
            DB::rollback();

            //  写日志
            \Log::info('文章修改失败'.$e->getMessage());

            //  返回
            return redirect()->back()->with('msg',$e->getMessage());
        }

        //  返回
        return redirect('/admin/article/list');

    }

    /**
     * @desc  删除操作
     * @param $id
     */
    public function del($id)
    {
        //事务的执行
        try{

            //  开始事务
            DB::beginTransaction();

            //  删除分类和内容
            $this->article->del($id);
            $this->content->del($id);

            //  写日志
            \Log::info('文章修改成功');

            //  提交事务
            DB::commit();
        }catch(\Exception $e){

            //  事物回滚
            DB::rollback();

            //  写日志
            \Log::info('文章删除失败'.$e->getMessage());

        }

        //  返回
        return redirect('/admin/article/list');

    }

}
