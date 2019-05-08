<?php

namespace App\Http\Controllers\Api;

use App\Model\Goods;
use App\Tools\ToolsCurl;
use App\Tools\ToolsOss;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeChatController extends Controller
{

    protected $wechat = null;

    protected $redis = null;

    protected $goods = null;

    protected $accessTokenKey = "access_token_cache";

    public function __construct()
    {

        //  获取微信的配置信息
        $this->wechat = \Config::get('wechat');

        $this->redis = new \Redis();

        $this->goods = new Goods();

        $this->redis->connect(env('REDIS_HOST'), env('REDIS_PORT'));

    }


    /**
     * 微信公众号的入口路由
     *
     * @param Request $request
     */
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

        //  接收微信服务器发送过来的xml数据
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : file_get_contents("php://input");

        \Log::info('用户发送的信息内容',[$postStr]);

        //  自定义消息分发记录
        $this->responseMsg($postStr);

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
                'name' => "其他",
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

            ],

            [
                'name' => '微网站',
                'type' => 'view',
                'url'  => 'http://www.zhumengyang.com/api/wap/getCode',
            ]

        ];

        //  转化字符为 unicode编码
        $res = ToolsCurl::httpCurl($menuUrl, "post", jsondecode($button,JSON_UNESCAPED_UNICODE));

        \Log::info('调用自定义菜单接口返回数据:', [$res]);

        return $res;

    }


    /**
     * 自定义消息分发回复
     *
     * @param $postStr
     */
    public function responseMsg($postStr)
    {

        if(!empty($postStr)) {

            //  解析xm的内容，微信服务器发过来的内容
            libxml_disable_entity_loader(true);
            //  xml转为对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            //  发送过来的消息类型
            $msgType = $postObj->MsgType;

            //  按照消息类型分发消息
            switch ($msgType) {
                case 'text'://  文本形式
                    $this->responseNews($postObj);
                    break;

                case 'image'://  图片形式
                    $this->responseImage($postObj);
                    break;

                case 'voice'://  语音形式
                    $this->responseVoice($postObj);
                    break;

                default:
                    break;

            }

        } else {

            echo "please input something";
            exit;

        }

    }


    /**
     * @desc  回复文本消息
     *
     * @param $postObj
     */
    public function responseText($postObj)
    {

        //  发送者
        $fromUserName = $postObj->FromUserName;
        //  接收者
        $toUserName = $postObj->ToUserName;
        //  用户输入内容
        $keywords = trim($postObj->Content);

        if(empty($keywords)) {
            $content = "您没有输入内容";
        } else {

            //  获取商品表的方法进行查询
            $goodsInfo = $this->goods->getGoodsByKeywords($keywords);

            if(empty($goodsInfo)) {
                $content = "没有与查询到内容";
            } else {
                $content = "商品名称".$goodsInfo->goods_name."\n 商品货号:".$goodsInfo->goods_sn."\n 商品价格：".$goodsInfo->market_price."\n 商品库存：".$goodsInfo->goods_num;

            }

        }

        \Log::info('记录用户发送文本消息',[$fromUserName, $toUserName, $keywords]);

        //  回复文本消息的模板
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";

        //  回复消息的内容
        $responseMsg = sprintf($textTpl, $fromUserName, $toUserName, time(), 'text', $content);

        \Log::info('自动回复消息:',[$responseMsg]);

        echo $responseMsg;

    }


    /**
     * 自动回复图文信息
     *
     * @param $postObj
     */
    public function responseNews($postObj)
    {

        //  发送者
        $fromUserName = $postObj->FromUserName;
        //  接收者
        $toUserName = $postObj->ToUserName;
        //  用户输入内容
        $keywords = trim($postObj->Content);

        if(empty($keywords)) {
            echo "您没有输入内容";
        } else {

            //  获取商品表的方法进行查询
            $goodsInfo = $this->goods->getGoodsByKeywords($keywords);

            if(empty($goodsInfo)) {

                //  回复文本消息的模板
                $textTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                            </xml>";

                //  回复消息的内容
                $responseMsg = sprintf($textTpl, $fromUserName, $toUserName, time(), 'text', '没有查询到内容');

                \Log::info('自动回复消息:',[$responseMsg]);

                echo $responseMsg;

            } else {

                //  获取商品图片地址
                $gallery = \DB::table('jy_goods_gallery')->select('image_url')->where('goods_id',$goodsInfo->id)->first();

                if(!empty($gallery)) {
                    $oss = new ToolsOss();

                    $imageUrl = $oss->getUrl($gallery->image_url, true);
                } else {
                    $imageUrl = "https://www.zhumengyang.com/images/photos/blog4.jpg";
                }


                //  图文消息的模板
                $newsTpl = "<xml>
                              <ToUserName><![CDATA[%s]]></ToUserName>
                              <FromUserName><![CDATA[%s]]></FromUserName>
                              <CreateTime>%s</CreateTime>
                              <MsgType><![CDATA[news]]></MsgType>
                              <ArticleCount>1</ArticleCount>
                              <Articles>
                                <item>
                                  <Title><![CDATA[%s]]></Title>
                                  <PicUrl><![CDATA[%s]]></PicUrl>
                                  <Url><![CDATA[%s]]></Url>
                                </item>
                              </Articles>
                            </xml>";

                $responseMsg = sprintf($newsTpl, $fromUserName, $toUserName, time(), $goodsInfo->goods_name, $imageUrl, 'http://www.baidu.com');

                echo $responseMsg;

            }

        }

    }


    /**
     * 自动回复图片信息
     *
     * @param $postObj
     */
    public function responseImage($postObj)
    {

        //  发送者
        $fromUserName = $postObj->FromUserName;
        //  接收者
        $toUserName = $postObj->ToUserName;
        //  图片地址
        $picUrl = $postObj->picUrl;
        //  通过素材管理中的接口上传多媒体文件，得到的id
        $mediaId = $postObj->MediaId;

        \Log::info('记录用户发送图片消息:',[$fromUserName, $toUserName, $picUrl, $mediaId]);

        $imageTpl = "<xml>
                         <ToUserName><![CDATA[%s]]></ToUserName>
                         <FromUserName><![CDATA[%s]]></FromUserName>
                         <CreateTime>%s</CreateTime>
                         <MsgType><![CDATA[image]]></MsgType>
                         <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                         </Image>
                     </xml>";

        //  回复消息的内容
        $responseMsg = sprintf($imageTpl, $fromUserName, $toUserName, time(), $mediaId);

        \Log::info('被动回复图片消息:',[$responseMsg]);

        echo $responseMsg;

    }


    /**
     * 自动回复语音的消息
     *
     * @param $postObj
     */
    public function responseVoice($postObj)
    {

        //  发送者
        $fromUserName = $postObj->FromUserName;
        //  接收者
        $toUserName = $postObj->ToUserName;
        //  通过素材管理中的接口上传多媒体文件，得到的id
        $mediaId = $postObj->MediaId;

        \Log::info('记录用户发送语音消息:',[$fromUserName, $toUserName, $mediaId]);

        $voiceTpl = "<xml>
                         <ToUserName><![CDATA[%s]]></ToUserName>
                         <FromUserName><![CDATA[%s]]></FromUserName>
                         <CreateTime>%s</CreateTime>
                         <MsgType><![CDATA[voice]]></MsgType>
                         <Voice>
                            <MediaId><![CDATA[%s]]></MediaId>
                         </Voice>
                     </xml>";

        //  回复消息的内容
        $responseMsg = sprintf($voiceTpl, $fromUserName, $toUserName, time(), $mediaId);

        \Log::info('被动回复语音消息:',[$responseMsg]);

        echo $responseMsg;

    }


    /**
     * 获取地理位置消息
     *
     * @param $postObj
     */
    public function responseLocation($postObj)
    {

        //  发送者
        $fromUserName = $postObj->FromUserName;
        //  接收者
        $toUserName = $postObj->ToUserName;
        //  经度
        $locationX = $postObj->location_X;
        //  维度
        $locationY = $postObj->location_Y;
        //  详细地址
        $label = $postObj->Label;

        $content = "您当前的位置信息：维度是：".$locationX."\n 经度是：".$locationY."\n 地理位置信息是：".$label;

        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";

        //  回复消息内容
        $responseMsg = sprintf($textTpl, $fromUserName, $toUserName, time(), 'text', $content);

        \Log::info('自动回复消息',[$responseMsg]);

        echo $responseMsg;

    }

    //获取access_token的值
    public function getAccessToken()
    {
        //获取缓存中的token值
        $accessToken = $this->redis->get($this->accessTokenKey);

        if(empty($accessToken)){
            //请求获取access_token的接口
            $accessTokenUrl = sprintf($this->wechat['access_token_url'], $this->wechat['app_id'],$this->wechat['app_secret']);

            \Log::info('请求获取access_token的接口url地址',['access_token_url'=>$accessTokenUrl]);

            //请求access_token;
            $response = ToolsCurl::httpCurl($accessTokenUrl);

            \Log::info('获取到的access_token接口返回的数据:',[$response]);

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
        $tmpStr = sha1($tmpStr);

        if($tmpStr != $signature) {
            return false;
        }

        return true;

    }


}
