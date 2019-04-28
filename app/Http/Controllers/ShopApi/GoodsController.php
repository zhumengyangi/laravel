<?php

namespace App\Http\Controllers\ShopApi;

use App\Model\Goods;
use App\Model\GoodsGallery;
use App\Model\GoodsSku;
use App\Tools\ToolsOss;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{


    /**
     * @desc  商品详情接口
     * @param $goodsId
     */
    public function detail($goodsId)
    {

        //  成功2000
        $return = [
            'code' => 2000,
            'msg'  => '商品详情接口'
        ];

        //  商品基本信息
        $goods = new Goods();
        $goodsInfo = $this->getDataInfo($goods, $goodsId)->toArray();

        //  商品相册信息
        $goodsGallery = new GoodsGallery();
        $gallers = $this->getDataList($goodsGallery, ['goods_id' => $goodsId]);

        //  图片
        $oss = new ToolsOss();

        foreach($gallers as $key => $value){
            $gallers[$key]['image_url'] = $oss->getUrl($value['image_url'],true);
        }

        //  实例化sku
        $goodsSku = new GoodsSku();

        //  商品的spu的信息
        $spu = $goodsSku->getSpuHandle($goodsId);
        //  商品sku的属性值
        $sku = $goodsSku->getSkuList($goodsId);


        //  用于存放sku的数据
        $sku_data = [];

        //  组装前台sku的数据
        foreach($sku as $key => $value){
            if(!isset($sku_data[$value['attr_id']])){// 如果不存在
                $sku_data[$value['attr_id']] = [
                    //  没有一级创建一个
                    'attr_name' => $value['attr_name'],
                    'attr_sku'  => [
                        [
                            'sku_value' => $value['sku_value'],
                            'attr_price' => $value['attr_price']
                        ]
                    ]
                ];
            }else{
                //  有，则增加
                $sku_data[$value['attr_id']]['attr_sku'][] = [
                    'sku_value' => $value['sku_value'],
                    'attr_price' => $value['attr_price']
                ];
            }
        }

        $return['data'] = [
            'goods'   => $goodsInfo,
            'gallery' => $gallers,
            'spu'     => $spu,
            'sku'     => $sku_data
        ];

        $this->returnJson($return);

    }


    /**
     * @desc  获取商品sku属性的列表信息
     * @param Request $request
     */
    public function getGoodsAttr(Request $request)
    {

        //  传过来 sku的ids
        $sku_ids = $request->input('sku_ids');

        //  字符串分割为数组
        $sku_ids = explode(',',$sku_ids);

        //  查找数据
        $sku = \DB::table('jy_goods_sku')->select('attr_id','sku_value')->whereIn('id',$sku_ids)->get();

        //  用于填装sku数据
        $skuData = [];

        foreach($sku as $key => $value){
            //  获取属性名
            $attr = \DB::table('jy_goods_attr')->select('attr_name')->where('id',$value->attr_id)->first();
            $skuData[$key]['sku_value'] = $value->sku_value;
            $skuData[$key]['attr_name'] = $attr->attr_name;

        }

        $this->returnJson($skuData);

    }

}
