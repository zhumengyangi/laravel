<?php

namespace App\Http\Controllers\Api;

use App\Tools\ToolsCurl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeChatController extends Controller
{

    protected $wechat = null;

    protected $redis = null;

    protected $accessTokenKey = "access_token_cache";

    public function __construct()
    {

        $this->wechat = \Config::get('wechat');

        $this->redis = new \Redis();

        $this->redis->connect(env('REDIS_HOST'), env('REDIS_PORT'));

    }

    public function index(Request $request)
    {

        $params = $request->all();

        \Log::info('微信公众平台请求数据：',[$params]);

        //  验证微信服务器请求签名的有效性
        /*$res = $this->checkSignature($params);

        if($res) {
            echo $params['echostr'];
        } else {
            echo "校验失败";
        }
        exit;*/

        //  获取微信公众号的自定义菜单栏
        $this->getSelfMenu();

    }


    /**
     * @获取微信公众号的自定义菜单
     *
     * @return mixed
     */
    public function getSelfMenu()
    {

        //  获取access_token的值
        $accessToken = $this->getAccessToken();

        $menuUrl = sprintf($this->wechat['menu_url'], $accessToken);

        \Log::info('获取微信公众号的自定义菜单接口的url地址', ['menu_url' => $menuUrl]);


        //  自定义菜单的内容
        $button['button'] = [

            [
                'type' => "click",
                'name' => "首页",
                'key'  => "index"
            ],

            [
                'name' => "我的菜单",
                'sub_button' => [

                    [
                        'name' => "网站后台",
                        'type' => "view",
                        'url' => "http://www.zhumengyang.com/admin/login",
                    ],
                    [
                        'name' => "某个梦",
                        'type' => "miniprogram",
                        'url' => "http://mp.weixin.qq.com",
                        'appid' => "wxdd8f819d873ca626",
                        'pagepath' => "pages/home/home",

                    ]

                ]

            ]

        ];

        //  转化字符为 unicode编码
        $res = ToolsCurl::httpCurl($menuUrl, "post", jsondecode($button,JSON_UNESCAPED_UNICODE));

        \Log::info('调用自定义菜单接口返回数据:', [$res]);

        return $res;

    }


    /**
     * 获取access_token的值
     *
     * @return bool|string
     */
    public function getAccessToken()
    {

        //  获取缓存中的token值
        $accessToken = $this->redis->get($this->accessTokenKey);

        if(empty($accessToken)) {

            //  请求获取access_token的接口
            $accessTokenUrl = sprintf($this->wechat['access_token_url'], $this->wechat['app_id'], $this->wechat['app_secret']);

            \Log::info('请求获取access_token的接口url地址', ['access_token_url' => $accessTokenUrl]);

            //  请求access_token
            $response = ToolsCurl::httpCurl($accessTokenUrl);

            \Log::info('获取到的access_token接口返回的数据：',[$response]);

            $accessToken = $response['access_token'];

        }

        return $accessToken;

    }


    /**
     * 验证微信服务器传输数据签名的有效性
     *
     * @param $params
     * @return bool
     */
    private function checkSignature($params)
    {

        $signature = isset($params['signature']) ?? "";
        $nonce = isset($params['nonce']) ?? null;
        $timestamp = isset($params['timestamp']) ?? null;

        $token = $this->wechat['token'];

        //  组装成数组
        $tmpArr = array($token, $timestamp, $nonce);

        //  对数组进行sort排序
        sort($tmpArr, SORT_STRING);

        $tmpStr = implode($tmpArr);
        $tmpStr = shal($tmpStr);

        if($tmpStr != $signature) {
            return false;
        }

        return true;

    }


}
