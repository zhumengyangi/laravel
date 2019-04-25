<?php

namespace App\Http\Controllers\ShopApi;

use App\Model\Member;
use App\Model\MemberInfo;
use App\Tools\ToolsSms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{

    /**
     * @desc  发送短信验证码的接口
     * @param Request $request
     */
    public function sendSms(Request $request)
    {

        //  获取手机号
        $phone = $request->input('phone');

        //  成功时
        $return = [
            'code' => 2000,
            'msg'  => '短信发送成功',
        ];


        //  判断手机号不能为空
        if(empty($phone)){
            $return = [
                'code' => 4001,
                'msg'  => '手机号不能为空',
            ];

            $this->returnJson($return);

        }

        //  验证手机号格式
        if(!preg_match("/^1[34578]\d{9}$/", $phone)){

            $return = [
                'code' => 4002,
                'msg'  => '手机号格式不正确',
            ];

            $this->returnJson($return);

        }

        //  实例化redis
        $redis = new \Redis();

        //  链接redis
        $redis->connect(env("REDIS_HOST"), env("REDIS_PORT"));

        //  校验手机号发送的次数
        //  当前手机号已经发送过的短信验证码的次数key
        $key1 = $phone."_NUMS";
        $nums = $redis->get($key1);

        //  每个手机号每天不能超过三次
        if($nums >=3){

            $return = [
                'code' => 4003,
                'msg'  => '今天短信发送次数已达上限，请二十四小时后再来',
            ];

            $this->returnJson($return);

        }

        //  六位数随机验证码
        $code = rand(100000,999999);

        //  存储验证码的key
        $key = "REGISTER_".$phone."_CODE";

        //  将信息写入日志
        \Log::info('手机号'.$phone.'发送短信验证码为：'.$code);

        //  设置redis值
        $redis->setex($key, 1800,$code);

        //  发送短信验证码
        $res = ToolsSms::sendSms($phone, $code);

        //  短信发送失败
        if(!$res['status']){

            $return = [
                'code' => 4004,
                'msg'  => $res['msg']
            ];

            $this->returnJson($return);

        }

        //  给用户短信发送次数 ++  （decy -- (by数量)）
        $redis->incr($key1);

        //  设置过期时间24h
        $redis->expire($key1,24*3600);

        //  json格式返回
        $this->returnJson($return);

    }


    /**
     * @desc  用户注册功能接口
     * @param Request $request
     */
    public function register(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  成功2000
        $return = [
            'code' => 2000,
            'msg'  => '注册成功'
        ];

        //  实例化redis
        $redis = new \Redis();

        //  链接redis
        $redis->connect(env("REDIS_HOST"), env("REDIS_PORT"));

        //  获取缓存存储的短信验证吗的值
        $code = $redis->get("REGISTER_".$params['phone']."_CODE");

        //  校验前台传来的和缓存中是否一致
        if($code != $params['code']){

            $return = [
                'code' => 4001,
                'msg'  => '手机验证码错误，请重新输入'
            ];

            $this->returnJson($return);

        }

        //  删除验证码
        $redis->del("REGISTER_".$params['phone']."_CODE");


        try{//  用户注册使用事务

            //  开启事务
            \DB::beginTransaction();

            //  添加到user主表信息
            $member = new Member();

            //  组装数据
            $data = [
                'phone' => $params['phone'],
                'password' => md5($params['password'])
            ];

            //  根据user_id添加
            $userId = $this->storeDataGetId($member, $data);

            //  实例化会员详情
            $memberInfo = new MemberInfo();

            //  组装数据 user_id 和邀请码
            $data1 = [
                'user_id' => $userId,
                'invite_code' => rand(100000,999999)
            ];

            //  添加会员详情表
            $this->storeData($memberInfo, $data1);

            //  提交事务
            \DB::commit();

        }catch(\Exception $e){

            //  事务回滚
            \DB::rollback();

            //  写入日志
            \Log::error('用户注册失败'.$e->getMessage());

            //  返回错误信息
            $return = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage()
            ];

        }

        //  json格式返回
        $this->returnJson($return);

    }


    /**
     * @desc  登录接口
     * @param Request $request
     */
    public function login(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  成功时
        $return = [
            'code' => 2000,
            'msg'  => '登录成功',
        ];


        //  判断手机号不能为空
        if(!isset($params['phone']) || empty($params['phone'])){

            $return = [
                'code' => 4001,
                'msg'  => '手机号不能为空',
            ];

            $this->returnJson($return);

        }

        //  判断密码不能为空
        if(!isset($params['password']) || empty($params['password'])  ){

            $return = [
                'code' => 4002,
                'msg'  => '密码不能为空',
            ];

            $this->returnJson($return);

        }

        //  通过手机号查询判断用户是否存在
        $userInfo = \DB::table('jy_user')->where(['phone'=>$params['phone']])->first();


        if(empty($userInfo)){

            //  不存在的时候
            $return = [
                'code' => 4003,
                'msg'  => '用户不存在'
            ];

            $this->returnJson($return);
        }else{

            //  存在的时候
            $postPwd = md5($params['password']);

            //  判断密码是否错误
            if($userInfo->password != $postPwd){

                $return = [
                    'code' => 4004,
                    'msg'  => '用户密码错误'
                ];

                $this->returnJson($return);

            }

            //  生成token的sql语句
            $data = \DB::select('select replace(uuid(),"-","") as token');

            $token = $data[0]->token;

            //  实例化redis
            $redis = new \Redis();
            //  链接redis
            $redis->connect(env("REDIS_HOST"), env("REDIS_PORT"));

            //  把用户生成的token存入redis 设置过期时间2h
            $redis->setex($token, 7200, $params['phone']);

            //  把token值返回给用户
            $return['data'] = $token;

            $this->returnJson($return);

        }

    }


    /**
     * @desc  执行退出的操作
     * @param Request $request
     */
    public function logout(Request $request)
    {

        //  获取token值
        $token = $request->input('token');

        $return = [
            'code' => 2000,
            'msg'  => '退出成功'
        ];


        if(empty($token)){

            $return = [
                'code' => 4001,
                'msg'  => 'token不能为空'
            ];

            $this->returnJson($return);

        }

        //  实例化redis
        $redis = new \Redis();

        //  链接redis
        $redis->connect(env("REDIS_HOST"),env("REDIS_PORT"));

        //  删除该用户的token
        $redis->del($token);

        $this->returnJson($return);

    }


    /**
     * @desc  校验token值
     * @param Request $request
     */
    public function token(Request $request)
    {

        $params = $request->all();

        $return = [
            'code' => 2000,
            'msg'  => '登录成功'
        ];

        if(!isset($params['token']) || empty($params['token'])){

            $return = [
                'code' => 4001,
                'msg'  => 'token不能为空'
            ];

            $this->returnJson($return);

        }

        $res = $this->checkToken($params['token']);

        if($res['status'] == false){
            $return = [
                'code' => 4002,
                'msg'  => 'token值不合法'
            ];

            $this->returnJson($return);

        }

        $return['data'] = $res['data'];

        $this->returnJson($return);

    }


}
