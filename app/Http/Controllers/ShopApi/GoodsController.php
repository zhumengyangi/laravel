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


    public function detail($goodsId)
    {

        $return = [
            'code' => 2000,
            'msg'  => '商品详情接口'
        ];

        $goods = new Goods();
        $goodsInfo = $this->getDataInfo($goods, $goodsId)->toArray();

        $goodsGallery = new GoodsGallery();
        $gallers = $this->getDataList(goodsGallery, ['goods_id' => $goodsId]);

        $oss = new ToolsOss();

        foreach($gallers as $key => $value){
            $gallers['$key']['image_url'] = $oss->getUrl($value['image_url'],true);
        }

        $goodsSku = new GoodsSku();
        $spu = $goodsSku->getSpuHandle($goodsId);

        $sku = $goodsSku->getSkuList($goodsId);

        $sku_data = [];

        foreach($sku as $key => $value){
            if(!isset($sku_data[$value['attr_id']])){
                $sku_data[$value['attr_id']] = [
                    'attr_name' => $value['attr_name'],
                    'attr_sku'  => [
                        [
                            'sku_value' => $value['sku_value'],
                            'attr_price' => $value['attr_price']
                        ]
                    ]
                ];
            }else{
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
