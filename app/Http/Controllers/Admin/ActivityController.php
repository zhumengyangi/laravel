<?php

namespace App\Http\Controllers\Admin;

use App\Model\Activity;
use App\Model\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{

    /**
     * @desc  活动列表页面
     */
    public function list()
    {

        //  实例化
        $activity = new Activity();

        //  获取数据
        $assign['activitys'] = $this->getDataList($activity);

        //  返回
        return view('admin.activity.list',$assign);

    }


    /**
     * @desc  添加页面
     */
    public function add()
    {

        //  返回
        return view('admin.activity.add');

    }

    /**
     * @desc  执行添加
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  处理支付方式的配置信息
        if(!empty($params['activity_config'])){

            //  设置空值
            $activity_config_config = [];

            //  处理 |
            $arr = explode('|',$params['activity_config']);

            //  循环处理数据
            foreach($arr as $key => $value){

                //  去除 =>
                $arr1 = explode("=>", $value);
                //  循环添加
                $activity_config_config[$arr1[0]] = $arr1[1];

            }

            $params['activity_config'] = serialize($activity_config_config);

        }

        //  实例化
        $activity = new Activity();

        //  执行添加
        $res = $this->storeData($activity, $params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','添加活动失败');
        }

        //  返回
        return redirect('/admin/activity/list');

    }

    /**
     * @desc  编辑页面
     * @param $id
     */
    public function edit($id)
    {

        //  实例化
        $activity = new Activity();

        //  获取数据的公共方法操作并转成数组
        $assign['info'] = $this->getDataInfo($activity, $id)->toArray();

        //  反序列化数据
        $activity_config = unserialize($assign['info']['activity_config']);

        $string = "";

        //  拼接原数据
        foreach($activity_config as $key => $value){
            $string .= $key. "=>" .$value. "|";
        }

        //  删除最后多的那个数据
        $assign['info']['activity_config'] = substr($string, 0,-1);

        //  返回
        return view('admin.activity.edit',$assign);

    }


    /**
     * @desc 执行编辑操作
     * @param Request $request
     */
    public function save(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  处理支付方式的配置信息
        if(!empty($params['activity_config'])){

            //  设置空值
            $activity_config = [];

            //  处理 |
            $arr = explode('|',$params['activity_config']);

            //  循环处理数据
            foreach($arr as $key => $value){

                //  去除 =>
                $arr1 = explode("=>", $value);
                //  循环添加
                $activity_config[$arr1[0]] = $arr1[1];

            }

            //  序列化数据
            $params['activity_config'] = serialize($activity_config);

        }

        //  根据id查到该数据
        $activity = Activity::find($params['id']);

        //  保存
        $res = $this->storeData($activity, $params);

        //  判断是否添加成功
        if(!$res){
            return redirect()->back()->with('msg','编辑活动失败');
        }

        //  返回
        return redirect('/admin/activity/list');


    }



    /**
     * @desc  删除活动
     * @param $id
     */
    public function del($id)
    {

        //  实例化
        $activity = new Activity();

        //  执行删除
        $res = $this->delData($activity, $id);

        //  返回
        return redirect('/admin/activity/list');

    }

}
