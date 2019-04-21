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

