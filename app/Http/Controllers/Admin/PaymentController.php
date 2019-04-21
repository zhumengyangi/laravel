<?php

namespace App\Http\Controllers\Admin;

use App\Model\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{


    /**
     * @desc 支付方式列表
     */
    public function list()
    {

        //  实例化
        $payment = new Payment();

        //  获取数据
        $assign['payments'] = $this->getDataList($payment);

        //  返回
        return view('admin.payment.list',$assign);

    }


    /**
     * @desc  添加页面
     */
    public function add()
    {

        return view('admin.payment.add');

    }

    /**
     * @desc  执行添加的操作
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  处理支付方式的配置信息
        if(!empty($params['pay_config'])){

            //  设置空值
            $pay_config = [];

            //  处理 |
            $arr = explode('|',$params['pay_config']);

            //  循环处理数据
            foreach($arr as $key => $value){

                //  去除 =>
                $arr1 = explode("=>", $value);
                //  循环添加
                $pay_config[$arr1[0]] = $arr1[1];

            }

            $params['pay_config'] = serialize($pay_config);

         }

        //  实例化
        $payment = new Payment();

        //  保存
        $res = $this->storeData($payment, $params);

        //  判断是否添加成功
        if(!$res){
            return redirect()->back()->with('msg','添加支付方式失败');
        }

        //  返回
        return redirect('/admin/payment/list');

    }

    /**
     * @desc  编辑
     * @param $id
     */
    public function edit($id)
    {

        //  实例化
        $payment = new Payment();


        //  通过id获取参数并转化为数组
        $assign['info'] = $this->getDataInfo($payment, $id)->toArray();

        //  序列化
        $pay_config = unserialize($assign['info']['pay_config']);

        $string = "";

        //  循环拼接赋值
        foreach ($pay_config as $key => $value) {
            $string .= $key."=>".$value."|";
        }

        //  处理最后一个多的 |
        $assign['info']['pay_config'] = substr($string,0,-1);

        //  返回
        return view('admin.payment.edit',$assign);

    }


    /**
     * @desc  执行编辑页面
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  处理支付方式的配置信息
        if(!empty($params['pay_config'])){

            //  设置空值
            $pay_config = [];

            //  处理 |
            $arr = explode('|',$params['pay_config']);

            //  循环处理数据
            foreach($arr as $key => $value){

                //  去除 =>
                $arr1 = explode("=>", $value);
                //  循环添加
                $pay_config[$arr1[0]] = $arr1[1];

            }

            //  序列化数据
            $params['pay_config'] = serialize($pay_config);

        }

        //  根据id查到该数据
        $payment = Payment::find($params['id']);

        //  保存
        $res = $this->storeData($payment, $params);

        //  判断是否添加成功
        if(!$res){
            return redirect()->back()->with('msg','编辑支付方式失败');
        }

        //  返回
        return redirect('/admin/payment/list');

    }


    /**
     * @desc  支付方式删除
     * @param $id
     */
    public function del($id)
    {

        //  实例化
        $payment = new Payment();

        //  执行删除
        $this->delData($payment, $id);

        //  返回
        return redirect('/admin/payment/list');

    }


}
