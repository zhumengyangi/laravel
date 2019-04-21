<?php

namespace App\Http\Controllers\Admin;

use App\Model\GoodsGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsGalleryController extends Controller
{


    /**
     * @desc  商品相册列表数据
     * @param $goodsId
     * @return mixed|string
     */
    public function getGallery($goodsId)
    {

        //  成功返回值
        $return = [
            'code' => 2000,
            'msg'  => '获取列表成功'
        ];

        //  实例化
        $gallery = new GoodsGallery();

        //  条件
        $where = [
            'goods_id' => $goodsId
        ];

        //  获取没有分页的数据列表
        $list = $this->getDataList($gallery, $where);

        //  返回数据
        $return['data'] = $list;

        //  返回
        return json_encode($return);

    }



    public function del($id)
    {

        //  返回
        $return = [
            'code' => 2000,
            'smg'  => '删除相册成功'
        ];

        //  实例化
        $gallery = new GoodsGallery();

        //  删除公共方法
        $res = $this->delData($gallery, $id);

        //  判断是否删除成功
        if(!$res){

            $return = [
                'code' => 4000,
                'msg'  => '删除相册失败'
            ];

        }

        //  返回
        return json_encode($return);


    }

}
