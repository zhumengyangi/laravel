<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    /**
     *@desc  后台首页
     */
    public function home()
    {

        //  redis的缓存数据
        $admin_data_key = "admin.home_data";
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);

        $data = $redis->get($admin_data_key);

        //  如果redis缓存为空的话
        if(empty($data)){
            //  今天日期
            $today = date("Y-m-d");

            //  明天日期
            $tommorrow = date("Y-m-d",strtotime('+1 days'));

            //  一周前
            $lastWeek = date("Y-m-d",strtotime('-5 days'));

            /***********************[会员统计信息]******************************/

            //  会员总数
            $assign['member_nums'] = \DB::table('jy_user')->count('id');
            //  今日会员注册总数
            $assign['today_register_nums'] = \DB::table('jy_user')->where('created_at','>=',$today)->where('created_at','<',$tommorrow)->count('id');
            //  近一周会员注册量
            $assign['last_week_register'] = \DB::table('jy_user')->where('created_at','>=',$lastWeek)->where('created_at','<',$tommorrow)->count('id');


            //近一周会员注册走势图
            $member_data = \DB::table('jy_user')->select(\DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as date, count(id) as nums"))->where('created_at','>=',$lastWeek)->where('created_at','<',$tommorrow)->groupBy(\DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))->get();

            //  循环拼接该数据
            $dates = $registes = '';
            foreach ($member_data as $key => $value) {
                $dates .= "'".$value->date."',";
                $registes .= $value->nums.",";
            }

            //  近一周用户注册走势
            $assign['register_date'] = substr($dates, 0,-1);
            $assign['register_nums'] = substr($registes, 0,-1);
            /***********************[会员统计信息]******************************/


            /***********************[订单相关]******************************/
            //  订单总数
            $assign['order_nums'] = \DB::table('jy_order')->count('id');
            //  今日订单总数
            $assign['today_order_nums'] = \DB::table('jy_order')->where('created_at','>=',$today)->where('created_at','<',$tommorrow)->count('id');
            //  近一周订单量
            $assign['last_week_order'] = \DB::table('jy_order')->where('created_at','>=',$lastWeek)->where('created_at','<',$tommorrow)->count('id');

            /***********************[订单相关]******************************/


            /***********************[商品相关]******************************/
            //  商品总数
            $assign['goods_nums'] = \DB::table('jy_goods')->count('id');
            //  今日商品总数
            $assign['today_goods_nums'] = \DB::table('jy_goods')->where('created_at','>=',$today)->where('created_at','<',$tommorrow)->count('id');
            //  近一周商品量
            $assign['last_week_goods'] = \DB::table('jy_goods')->where('created_at','>=',$lastWeek)->where('created_at','<',$tommorrow)->count('id');

            /***********************[商品相关]******************************/

            //  设置redis 缓存过期时间 300s
            $res = $redis->setex($admin_data_key,300, json_encode($assign));

        }else{  //不为空则走缓存

            $assign = json_encode($data, true);

        }


        return view('admin.home.home',$assign);
    }

}
