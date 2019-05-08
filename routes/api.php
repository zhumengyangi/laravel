<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*#############################[首页相关]#############################*/

//  首页banner图接口
Route::post('home/banners','Api\HomeController@banners');
//  首页最新小说接口
Route::post('home/news','Api\HomeController@newsList');
//  首页点击排行接口
Route::post('home/clicks','Api\HomeController@clicksList');


//  分类列表接口
Route::post('category/list','Api\CategoryController@getCategory');
//  获取小说的分类列表
Route::post('category/novel','Api\CategoryController@getCategoryNovel');

//  获取小说搜索接口
Route::post('search/novel','Api\SearchController@getSearchList');
//  获取小说书单接口
Route::get('book/list','Api\NovelController@bookList');
//  获取小说阅读榜单接口
Route::post('read/rank','Api\NovelController@bookRank');

//  获取小说详情接口
Route::post('novel/detail/{id}','Api\NovelController@detail');
//  小说点击量接口
Route::any('novel/clicks/{id}','Api\NovelController@clicks');
//  小说阅读数接口
Route::any('novel/read/{id}','Api\NovelController@readNum');

//  获取小说章节接口
Route::post('chapter/list/{novel_id}','Api\ChapterController@chapterList');
//  小说内容详情接口
Route::post('chapter/info/{id}','Api\ChapterController@chapterInfo');

//  添加评论接口
Route::post('comment/add','Api\CommentController@add');
//  评论列表接口
Route::post('comment/list/{novelId}','Api\CommentController@list');
//  删除评论接口
Route::post('comment/del/{id}','Api\CommentController@del');


/*#############################[首页相关]#############################*/


/*#############################[电商类接口]#############################*/

Route::middleware('api_auth')->group(function (){

    //  商品分类接口
    Route::post('home/category', 'ShopApi\HomeController@category');

    //  首页banner图 广告位的接口
    Route::post('home/ad', 'ShopApi\HomeController@ad');

    //  商品类型接口
    Route::post('home/goods', 'ShopApi\HomeController@goodsList');

    //  品牌列表接口
    Route::post('home/brands', 'ShopApi\HomeController@brand');

    //  最新文章接口
    Route::post('home/newsArticle', 'ShopApi\HomeController@newsArticle');

    //  发送短信验证码接口
    Route::post('login/sendSms', 'ShopApi\LoginController@sendSms');

    //  用户注册功能接口
    Route::post('register', 'ShopApi\LoginController@register');

    //  用户登录退出功能
    Route::post('login','ShopApi\LoginController@login');
    Route::post('logout','ShopApi\LoginController@logout');

    //  用户登录的功能
    Route::post('token','ShopApi\LoginController@token');

    //  商品详情接口
    Route::post('user/detail/{id}','ShopApi\GoodsController@detail');

    //  用户详情接口
    Route::post('user/info{id}','ShopApi\UserController@userInfo');

    //  用户资金流水接口
    Route::post('user/modify','ShopApi\UserController@userModify');
    Route::post('user/fund/{user_id}','ShopApi\UserController@userFundHistory');

    //  用户中心设置地址信息
    Route::post('user/region/{fid}','ShopApi\UserController@getRegion');
    Route::post('user/address/add','ShopApi\UserController@addUserAddress');

    //  获取接口列表数据
    Route::post('user/address/list/{user_id}','ShopApi\UserController@getUserAddress');
    Route::post('user/default/address','ShopApi\UserController@setDefaultAddress');

    //  订单相关
    Route::post('user/order/{user_id}','ShopApi\OrderController@userOrder');

    //  下订单接口
    Route::post('user/order','ShopApi\OrderController@createOrder');

    //  订单信息
    Route::any('shipping','ShopApi\OrderController@shipping');
    Route::post('payment','ShopApi\OrderController@payment');

    //  用户中心红包记录
    Route::post('user/bonus/{user_id}','ShopApi\BonusController@userBonusList');

    //  支付相关的
    Route::any('alipay','ShopApi\AlipayController@alipay');
    //  同步回调地址
    Route::any('return/url','ShopApi\AlipayController@returnUrl');
    //  异步回调地址
    Route::any('notify/url}','ShopApi\AlipayController@notifyUrl');

});

/*#############################[电商类接口]#############################*/

//  微信公众号服务器配置
Route::any('wechat/index','Api\WeChatController@index');
//  获取code的授权码
Route::any('wap/getCode','Api\WapController@getCode');
//  网页授权的回调地址
Route::any('wap/callback','Api\WapController@callback');