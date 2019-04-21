<?php

namespace App\Http\Controllers\Admin;


use App\Tools\ToolsEmail;
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
     * @param
     * @return
     */
    public function logout(Request $request)
    {

        //  session删除
        $request->session()->forget('user');

        return redirect('/admin/login');

    }


    /**
     * @desc 忘记密码的页面
     */
    public function forget()
    {

        return view('admin.forget.forget');

    }


    /**
     * @desc 发送邮件的接口
     * @param Request $request
     */
    public function sendEmail(Request $request)
    {

        $email = $request->input('email','');
        $username = $request->input('username','');


        $return = [
            'code' => 2000,
            'msg'  => '发送成功'
        ];

        //  实例化
        $adminUsers = new AdminUsers();

        //  组装邮箱和用户名作为where查询条件
        $where = [
            'username' => $username,
            'email'    => $email
        ];

        //  通过where条件查询一条记录
        $data = $this->getDataInfoByWhere($adminUsers, $where);

        //  用户或邮箱不存在时候
        if(empty($data)){

            $return = [
                'code' => 4003,
                'msg'  => '用户或邮箱不存在'
            ];

            //  返回
            return json_encode($return);

        }

        try{

            //  拼接邮箱链接 跳转回来
            $url = sprintf(env('APP_URL')."/admin/forget/reset?username=%s&email=%s&activeCode=%s",$username, $email, ToolsEmail::createActiveCode($username, $email));

            //  发送的是html 邮件 视图数据
            $viewData = [
                'url' => 'admin.forget.email',
                'assign' =>[
                    'username' => $username,
                    'url'      => $url,
                ],
            ];

            //  邮件数据
            $emailData = [
                'email_address' => $email,
                'subject'       => "管理后台找回密码"
            ];

            //  发送邮件
            $res = ToolsEmail::sendHtmlEmail($viewData, $emailData);

        }catch (\Exception $e){

            //  返回错误信息
            $return = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage()
            ];

        }

        //  返回
        return json_encode($return);

    }


    /**
     * @desc  重置页面
     * @param Request $request
     */
    public function reset(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  验证参数
        if(empty($params)){
            return redirect('/admin/forget/password')->with('msg','参数不能为空');
        }

        //  实例化
        $adminUsers = new AdminUsers();

        //  设置where条件 通过用户名和邮箱
        $where = [
            'username' => $params['username'],
            'email'    => $params['email']
        ];

        //  通过where条件查询一条记录
        $data = $this->getDataInfoByWhere($adminUsers, $where);

        //  判断结果是否为空
        if(empty($data)){
            return redirect('/admin/forget/password')->with('msg','用户或邮箱不存在');
        }

        //  验证激活码
        $key = "FORGET_".$params['username']."_".$params['email'];

        //  存放于rides中
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        $activeCode = $redis->get($key);

        //  判断redis中的激活码和传过来的是否相同
        if($activeCode != $params['activeCode']){
            return redirect('/admin/forget/password')->with('msg','激活码不存在');
        }

        //  成功返回
        return view('admin.forget.reset', $params);

    }


    /**
     * @desc  重新设置密码
     * @param Request $request
     */
    public function save(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  判断密码和确认密码是否为空
        if(empty($params['password']) || empty($params['confirm_password'])){
            return redirect()->back()->with('msg','密码或确认密码不能为空');
        }

        //  判断两次的密码是否相同
        if($params['password'] != $params['confirm_password']){
            return redirect()->back()->with('msg','两次输入的密码不一致，请重新输入');
        }

        //  实例化
        $adminUsers = new AdminUsers();

        //  设置where条件 通过用户名和邮箱
        $where = [
            'username' => $params['username'],
            'email'    => $params['email']
        ];

        //  通过where查询一条数据
        $object = $this->getDataInfoByWhere($adminUsers, $where);

        //  给新设置的密码进行md5加密 然后添加
        $res = $this->storeData($object, ['password' => md5($params['password'])]);
        if(!$res){
            return redirect('/admin/forget/password')->with('msg','密码重置失败');
        }

        //  返回
        return redirect('/admin/login');

    }

}






















