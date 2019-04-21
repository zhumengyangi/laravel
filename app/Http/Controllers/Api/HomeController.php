<?php

namespace App\Http\Controllers\Api;

use App\Model\Novel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    /**
     * @desc  小说首页banner图
     * @param Request $request
     * @return string
     */
    public function banners(Request $request)
    {

        //  获取一共显示几张banner图
        $num = $request->input('num',3);

        //  实例化
        $novel = new Novel();

        //  调取该方法
        $list = $novel->getBanners();

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '获取banner成功',
            'data' => $list
        ];

        return json_encode($return);

    }


    /**
     * @desc  小说排行榜
     * @param Request $request
     * @return string
     */
    public function newsList(Request $request)
    {

        //  获取一共显示几个小说
        $num = $request->input('num',3);

        //  实例化
        $novel = new Novel();

        //  调取该方法
        $news = $novel->getNews();

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '获取最新小说成功',
            'data' => $news
        ];

        return json_encode($return);


    }



    /**
     * @desc  点击排行榜
     * @param Request $request
     * @return string
     */
    public function clicksList(Request $request)
    {

        //  获取一共显示几个小说
        $num = $request->input('num',3);

        //  实例化
        $novel = new Novel();

        //  调取该方法
        $clicks = $novel->getClicks();

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '获取点击量成功',
            'data' => $clicks
        ];

        return json_encode($return);

    }

}
