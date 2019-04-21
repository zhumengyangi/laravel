<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdminUsers;

class LoginController extends Controller
{
    //


    /**
     * @desc  登录页面
     * @param Request $request
     */
    public function index(Request $request)
    {

        //  echo md5('123qwe');exit;
        //  46f94c8de14fb36680850768ff1b7f2a
        //  获取session
        $session = $request->session();

        if($session->has('user')){//    如果存在session 信息的话不用登录
            return redirect('/admin/home');
        }

        return view('admin.login');

    }


    /* public  function  home(){
         return view('admin.home');
     }*/

    /**
     * @desc  执行登陆的页面
     * 1、现根据用户名查询账号是否存在
     * 2、如果不存在提示用户不存在
     * 3、校验密码是否正确
     * 4、如果正确登录成功,否则提示密码错误
     * @param Request $request
     */
    public function doLogin(Request $request)
    {

        //  获取全部数据
        $params = $request->all();

        $return = [
            'code' => 2000,
            'msg'  => '登录成功'
        ];

        //  用户名不能为空
        if(!isset($params['username']) || empty($params['username']))
        {

            $return = [
                'code' => 4001,
                'msg'  => '用户名不能为空'
            ];

            return json_encode($return);

        }

        //  密码不能为空
        if(!isset($params['password']) || empty($params['password']))
        {

            $return = [
                'code' => 4002,
                'msg'  => '密码不能为空'
            ];

            return json_encode($return);

        }


        //  通过用户名获取用户的信息
        $userInfo = AdminUsers::getUserByName($params['username']);

        //  用户不存在
        if(empty($userInfo)){

            $return = [
                'code' => 4003,
                'msg'  => '用户不存在'
            ];

            return json_encode($return);

        }else{

            //  传递过来的参数密码
            $postPwd = md5($params['password']);

            //  密码错误
            if($postPwd !== $userInfo->password){

                $return = [
                    'code' => 4004,
                    'msg'  => '密码不正确'
                ];

                return json_encode($return);

            }else{  //  密码正确，执行登录

                //  获取session对象
                $session = $request->session();

                //  存储用户ID、姓名、头像、是否超管
                $session->put('user.user_id', $userInfo->id);
                $session->put('user.username', $userInfo->username);
                $session->put('user.image_url', $userInfo->image_url);
                $session->put('user.is_super', $userInfo->is_super);

                return json_encode($return);

            }

        }

    }


    /**
     * @desc  用户退出功能
     * @param ID
     * @return
     */
    public function logout(Request $request)
    {

        //  session删除
        $request->session()->forget('user');

        return redirect('/admin/login');

    }

}






















