<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ad;
use App\Model\AdPosition;
use App\Tools\ToolsAdmin;
use App\Tools\ToolsOss;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdController extends Controller
{

    //  受保护的实例化
    protected $postion = null;
    protected $ad = null;

    /**
     * @desc  实例化类
     * AdController constructor.
     */
    public function __construct()
    {

        $this->position = new AdPosition();
        $this->ad = new Ad();

    }


    /**
     * @desc  广告列表页面
     */
    public function list()
    {

        //  获取广告位数据
        $assign['list'] = $this->ad->getAdList();

        //  实例化
        $oss = new ToolsOss();

        //  处理图片对象
        foreach($assign['list'] as $key => $value){

            //  从oss获取图片
            $value['image_url'] = $oss->getUrl($value['image_url'],true);

            //  重新赋值
            $assign['list'][$key] = $value;

        }


        //  返回
        return view('admin.ad.list',$assign);

    }


    /**
     * @desc  添加页面
     */
    public function add()
    {

        //  获取广告位列表
        $assign['position'] = $this->position->getList();

        //  返回
        return view('admin.ad.add',$assign);

    }

    /**
     * @desc  执行添加
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  判断图片是否为空
        if(!isset($params['image_url']) || empty($params['image_url'])){
            return redirect()->back()->with('msg','请先上传图片');
        }


        //  执行添加图片
        $params['image_url'] = ToolsAdmin::uploadFile($params['image_url']);

        //  删除_token
        $params = $this->delToken($params);

        //  实例化
        $ad = new Ad();

        //  执行添加
        $res = $this->storeData($ad, $params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','添加广告失败');
        }

        //  返回
        return redirect('/admin/ad/list');

    }

    /**
     * @desc  编辑页面
     * @param $id
     */
    public function edit($id)
    {

        //  实例化
        $ad = new Ad();

        //  获取数据的公共方法操作
        $assign['info'] = $this->getDataInfo($ad, $id);

        //  获取广告位列表
        $assign['position'] = $this->position->getList();

        //  返回
        return view('admin.ad.edit',$assign);

    }


    /**
     * @desc 执行编辑操作
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  判断图片是否为空
        if(isset($params['image_url']) && !empty($params['image_url'])){
            //return redirect()->back()->with('msg','请先上传图片');

            //  执行添加图片
            $params['image_url'] = ToolsAdmin::uploadFile($params['image_url']);

        }

        //  删除_token
        $params = $this->delToken($params);

        //  查询出来的对象
        $ad = Ad::find($params['id']);

        //  修改数据
        $res = $this->storeData($ad, $params);

        //  吸怪失败
        if(!$res){
            return redirect()->back()->with('msg','修改广告失败');
        }

        //  返回
        return redirect('/admin/ad/list');


    }



    /**
     * @desc  删除广告
     * @param $id
     */
    public function del($id)
    {

        //  实例化
        $ad = new Ad();

        //  执行删除
        $res = $this->delData($ad, $id);

        //  返回
        return redirect('/admin/ad/list');

    }

}
