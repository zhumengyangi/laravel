<?php

namespace App\Http\Controllers\ShopApi;

use App\Model\Article;
use App\Model\Brand;
use App\Model\Category;
use App\Tools\ToolsAdmin;
use App\Tools\ToolsOss;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{


    /**
     * @desc  商品分类的接口
     */
    public function category()
    {

        //  实例化
        $category = new Category();

        //  获取数据
        $data = $this->getDataList($category);

        //  分类树
        $data = ToolsAdmin::buildTree($data,0,"f_id");

        //  返回值
        $return = [
            'code' => 2000,
            'msg'  => '成功',
            'data' => $data
        ];

        //  返回
        $this->returnJson($return);

    }


    /**
     * @desc  首页广告位的接口
     * @param Request $request
     */
    public function ad(Request $request)
    {

        //  获取广告位的id
        $position = $request->input('postion_id',1);

        //  广告的条数
        $nums = $request->input('nums',1);

        //  当前时间
        $currentTime = date("Y-m-d H:i:s");

        //  查询想要的数据
        $ad = \DB::table('jy_ad')->select('id','ad_name','image_url','ad_link')
                              ->where('position_id',$position)
                              ->where('start_time','<',$currentTime)
                              ->where('end_time','>',$currentTime)
                              ->limit($nums)
                              ->get();

        //  组装广告的数据
        $ad_data = [];

        $toolsOss = new ToolsOss();

        foreach ($ad as $key => $value){

            $ad_data[$key] = [
                'id' => $value->id,
                'ad_name' => $value->ad_name,
                'image_url' => $toolsOss->getUrl($value->image_url,true),
                'ad_link' => $value->ad_link
            ];

        }

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '成功',
            'data' => $ad_data
        ];

        //  返回
        $this->returnJson($return);

    }


    /**
     * @desc  商品类型列表
     * @param Request $request
     */
    public function goodsList(Request $request)
    {

        //  获取商品的类型
        $type = $request->input('type',1);
        //  获取商品的个数
        $nums = $request->input('nums',5);


        if($type == 1){//  热卖商品

            $goods = \DB::table('jy_goods')->select('id','goods_name','market_price')
                                          ->where('is_hot',1)
                                          ->where('is_shop',1)
                                          ->limit($nums)
                                          ->get();

        }elseif($type == 2){//  推荐商品

            $goods = \DB::table('jy_goods')->select('id','goods_name','market_price')
                                          ->where('is_recommand',1)
                                          ->where('is_shop',1)
                                          ->limit($nums)
                                          ->get();

        }else{//  新品商品

            $goods = \DB::table('jy_goods')->select('id','goods_name','market_price')
                                          ->where('is_new',1)
                                          ->where('is_shop',1)
                                          ->limit($nums)
                                          ->get();

        }

        $goodsList = [];

        //  组装数据 oss图片
        $toolsOss = new ToolsOss();
        foreach($goods as $key => $value){
            $gallery = \DB::table('jy_goods_gallery')->where('goosd_id',$value->id)->first();
            $goodsList[$key] = [
                'id' => $value->id,
                'goods_name' => $value->goods_name,
                'market_price' => $value->market_price,
                'image_url' => !empty($gallery->image_url) ? $toolsOss->getUrl($gallery->image_url,true) : "",
            ];

        }

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '成功',
            'data' => $goodsList
        ];

        //  返回json
        $this->returnJson($return);

    }


    /**
     * @desc  品牌列表接口
     * @param Request $request
     */
    public function brand(Request $request)
    {

        //  想要的值
        $nums = $request->input('nums',9);

        //  实例化
        $object = new Brand();

        //  获取限制输出的条数
        $brand = $this->getLimitDataList($object, $nums, ['status' => 1]);

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => "成功",
            'data' => $brand
        ];

        //  返回json格式
        $this->returnJson($return);

    }


    /**
     * @desc  最新文章接口
     * @param Request $request
     */
    public function newsArticle(Request $request)
    {

        //  想要的值
        $nums = $request->input('nums',5);

        //  实例化
        $article = new Article();

        //  获取限制输出的条数
        $news = $article->getNewArticles($nums, ['status' => 3]);

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => "成功",
            'data' => $news
        ];

        //  返回json格式
        $this->returnJson($return);

    }


}
