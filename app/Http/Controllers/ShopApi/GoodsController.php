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

}
