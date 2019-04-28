<?php

namespace App\Http\Controllers\ShopApi;

use App\Model\Goods;
use App\Model\Order;
use App\Model\OrderGoods;
use App\Model\Payment;
use App\Model\Region;
use App\Model\Shipping;
use App\Model\UserAddress;
use App\Tools\ToolsAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{


    /**
     * @desc  用户订单列表
     * @param $userId
     */
    public function userOrder($userId)
    {

        //  成功返回2000
        $return = [
            'code' => 2000,
            'msg'  => '获取用户订单信息成功'
        ];

        //  实例化
        $order = new Order();
        //  通过user_id获取该用户的全部数据
        $data = $this->getDataList($order, ['user_id' => $userId]);

        //  实例化
        $region = new Region();
        //  循环将数据拿出来
        foreach($data as $key => $value){

            //  国家
            $country = $this->getDataInfo($region, $value['country']);
            $data[$key]['country'] = $country->region_name;

            //  省
            $province = $this->getDataInfo($region, $value['province']);
            $data[$key]['province'] = $province->region_name;

            //  市
            $city = $this->getDataInfo($region, $value['city']);
            $data[$key]['city'] = $city->region_name;

            //  区
            $district = $this->getDataInfo($region, $value['district']);
            $data[$key]['district'] = $district->region_name;

        }

        //  返回数据
        $return['data'] = $data;

        //  返回
        $this->returnJson($return);

    }


    /**
     * @desc  配送方式
     */
    public function shipping()
    {

        //  实例化
        $shipping = new Shipping();

        //  获取配送方式的数据
        $data = $this->getDataList($shipping);

        //  返回数据
        $this->returnJson($data);

    }


    /**
     * @desc  支付方式
     */
    public function payment()
    {

        //  实例化
        $payment = new Payment();

        //  获取支付方式的数据
        $data = $this->getDataList($payment);

        //  返回数据
        $this->returnJson($data);

    }


    /**
     * @desc  生成订单
     * @param Request $request
     */
    public function createOrder(Request $request)
    {

        //  成功返回2000
        $return = [
            'code' => 2000,
            'msg'  => '订单添加成功'
        ];

        //  获取全部参数
        $params = $request->all();

        try{
            //  开启事务
            \DB::beginTransaction();

            //  获取订单号
            $orderSn = ToolsAdmin::buildGoodsSn();

            //  实例化
            $object = new UserAddress();

            //  获取详细地址
            $address = $this->getDataInfo($object, $params['address']);

            $orderInfo = [
                'user_id' => $params['user_id'],//用户id
                'order_sn' => $orderSn, //  订单号
                'order_status' => 2,    //  订单状态(以确认)
                'shipping_status' => 1,//   配送状态（未发货）
                'pay_status' => 1,//    订单状态（未支付）
                'zcode' => rand(100000,999999),//   邀请码
                'consignee' => $address['consignee'],// 收货人姓名
                'country' => 1,//   国家
                'province' => $address['province'],// 省
                'city' => $address['city'],// 市
                'district' => $address['district'],//区
                'address' => $address['address'],// 详细地址
                'phone' => $address['moblie'],// 收货人手机号
                'pay_name' => $params['payment'],// 支付方式
                'shipping_name' => $params['shipping'],//配送方式
                'goods_price' => $params['goods_price'],//商品价格
                'shipping_fee' => $params['shipping_fee'],//配送费用
                'pay_price' => $params['pay_price'],//  支付总金额
                'paid_price' => 0,//    已支付的金额
                'bonus_price' => $params['bonus_price'],//  红包金额
                'note' => $params['note'],// 陪住信息
                'confirm_time' => date('Y-m-d H:i:s'),//    订单确认时间
                'pay_time' => date('Y-m-d H:i:s'),//    订单支付时间
            ];

            //  保存数据
            $order= new Order();
            $orderId = $this->storeDataGetId($order, $orderInfo);

            //  创建订单关联的商品信息
            $goodsInfo = json_decode($params['goods_info'], true);
            $orderGoodsData = [];

            //  循环赋值
            foreach($goodsInfo as $key => $value){

                $orderGoodsData[] =[
                    'goods_id' => $value['goods_id'],// 商品id
                    'order_id' => 0,// 订单id
                    'goods_name' => $value['goods_name'],// 商品名字
                    'goods_num' => $value['nums'],// 商品数量
                    'shop_price' => $value['shop_price'],// 商品市场价
                    'market_price' => $value['market_price'],// 本店售价
                    'goods_attr' => serialize($value['goods_attr']),// 商品属性
                ];

            }

            //  实例化  多条添加
            $orderGoods = new OrderGoods();
            $this->storeDataMany($orderGoods, $orderGoodsData);

            //  减订单库存
            foreach ($orderGoodsData as $k => $v){

                $goods = Goods::find($v['goods_id']);


                $data = [
                    'goods_num' => $goods->goods_num - $v['goods_num']
                ];

                //  在此保存数据
                $this->storeData($goods, $data);

            }

            //  返回订单编号
            $return['data'] = [
                'order_sn' => $orderSn
            ];

            //  提交事务
            \DB::commit();

        }catch (\Exception $e){

            //  事务回滚
            \DB::rollback();

            写入日志
            \Log::error('订单创建失败原因：'.$e->getMessage());

            //  返回错误信息
            $return = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage()
            ];
        }

        //  返回
        $this->returnJson($return);

    }


}
