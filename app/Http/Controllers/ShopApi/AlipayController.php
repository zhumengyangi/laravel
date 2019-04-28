<?php

namespace App\Http\Controllers\ShopApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Order;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

class AlipayController extends Controller
{

    //  支付宝接口
    protected  $config;

    public function __construct()
    {

        //  获取支付宝的配置信息
        $this->config = \Config::get('alipay');

    }


    /**
     * @desc  支付宝接口
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function alipay(Request $request)
    {

        //  获取传来的数据
        $order = $request->all();

        //  获取支付宝的配置信息
        $config = \Config::get('alipay');

        //  调取支付宝接口
        $alipay = Pay::alipay($config)->web($order);

        //  返回
        return $alipay;

    }


    /**
     * @desc  同步回调地址
     * @param Request $request*/
    public function returnUrl(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  写日志
        \Log::info('支付宝同步回调地址',[$params]);

        //  延缓5s后执行
        sleep(5);

        //  返回地址
        return redirect('http://www.360buy.com/index/goods/returnUrl?'.http_build_query($params));

    }


    /**
     * @desc  异步回调地址
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function notifyUrl(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  写日志
        \Log::info('支付宝异步回调返回的参数',[$params]);

        //  阿里云
        $alipay = Pay::alipay($this->config);

        //  使用抛出异常
        try{

            //  验签
            $data = $alipay->verify();

            \Log::info('支付宝异步回调验签数据：',[$data]);

            //  要修改的订单的数据
            $orderData = [
                'paid_price' => $data->buyer_pay_amount
            ];

            //  支付成功状态
            if($data->trade_status == "TRADE_SUCCESS" || $data->trade_status = "TRADE_FINISHED"){

                if($data->buyer_pay_amount != $data->total_amount){
                    $orderData['pay_status'] = 5;
                    \Log::info('修改订单的信息，部分支付成功：',[$orderData]);
                }

                $orderData['pay_status'] = 3;
                \Log::info('修改订单信息支付成功:',[$orderData]);

            }else{

                $orderData['pay_status'] = 4;
                \Log::info('修改订单信息支付失败:',[$orderData]);

                //  库存还原

            }

            //  更新订单
            Order::where('order_sn',$data->out_trade_no)->update($orderData);

            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况

            Log::info('Alipay notify',$data->all());

        }catch (\Exception $e){

            $e->getMessage();
        }

        //  直接连接
        return $alipay->success();


    }


}














