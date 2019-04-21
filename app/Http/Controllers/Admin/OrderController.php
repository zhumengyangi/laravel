<?php

namespace App\Http\Controllers\Admin;

use App\Model\Goods;
use App\Model\Member;
use App\Model\Order;
use App\Model\OrderGoods;
use App\Model\Region;
use App\Tools\ToolsAdmin;
use App\Tools\ToolsExcel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    /**
     *@desc  订单列表
     */
    public function list()
    {

        //  实例化
        $order = new Order();

        //  获取数据（分页）
        $assign['list'] = $this->getPageList($order);

        //  返回
        return view('admin.order.list',$assign);

    }


    /**
     * @desc  订单详情页面
     * @param $id
     */
    public function detail($id)
    {

        //  实例化
        $order = new Order();
        $orderGoods = new OrderGoods();
        $region = new Region();
        $member = new Member();

        //  订单基本信息
        $order = $this->getDataInfo($order,$id);


        //  收货人的的地址信息
        $country = $this->getDataInfo($region,$order->country);//   国家
        $province = $this->getDataInfo($region,$order->province);//   省
        $city = $this->getDataInfo($region,$order->city);//   市
        $district = $this->getDataInfo($region,$order->district);//   县
        //  组装数据
        $assign = [
            'country' => $country->region_name,
            'province' => $province->region_name,
            'city' => $city->region_name,
            'district' => $district->region_name,
        ];

        $assign['order'] = $order;

        //  订单商品信息
        $assign['order_goods'] = $this->getDataList($orderGoods);

        //  会员
        $assign['member'] = $this->getDataInfo($member, $order->user_id);

        //  返回
        return view('admin.order.detail',$assign);

    }


    /**
     * @desc  订单批量导入的功能
     */
    public function import()
    {

        return view('admin.order.import');

    }


    /**
     * @desc  执行导入的操作  备注：excel时间格式 需要设定为Y-m-d H:i:s
     * @param Request $request
     */
    public function doImport(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  获取要导入的文件
        $files = $params['file_name'];

        //  判断文件的后缀名
        if($files->extension() !="xls" && $files->extension()!="xlsx"){
            return redirect()->back()->with('msg','文件格式不正确,请上传xls或slsx文件');
        }

        //  excel文件的导入
        $data = ToolsExcel::import($files);

        //  实例化
        $goods = new Goods();

        //  设置一个空数组 给新的货号使用
        $goodsData = [];


        //  从新生成商品货号
        foreach ($data as $key => $value) {

            $value['goods_sn'] = ToolsAdmin::buildGoodsSn();

            $goodsData[$key] = $value;
        }


        //  执行多条添加
        $res = $this->storeDataMany($goods, $goodsData);

        //  导入失败
        if(!$res){

            return redirect()->back()->with('msg','导入失败');

        }

        //  返回
        return redirect('/admin/goods/list');

    }


    /**
     * @desc  商品导出的功能
     */
    public function export()
    {

        //  实例化
        $order = new Order();

        //  获取全部的值
        $data = $this->getDataList($order);

        //  导出的数据存放该数组中
        $exportData = [];

        //  excel的head头部 自己想要导出的数据
        $head = ['order_sn','goods_price','user_id','consignee','phone','shipping_name','pay_name'];

        $exportData[] = ['订单号','商品总价','用户id','收货人','收货人手机','配送方式','支付方式'];

        //  组装打印的数据
        foreach ($data as $key => $value){

            $tmpArr = [];
            foreach ($head as $column){
                $tmpArr[] = $value[$column];
            }

            $exportData[] = $tmpArr;

        }

        //  文件导出操作
        ToolsExcel::exportData($exportData,'订单数据');

    }


}
