<?php
namespace App\Tools;
use Mail;

class ToolsEmail
{


    /**
     * @desc 发送文本邮件
     * @param $emailData
     */
    public static function sendEmail($emailData)
    {

        $res = Mail::raw($emailData['content'], function ($message) use($emailData){
            $to = $emailData['email_address'];
            $message->to($to)->subject($emailData['subject']);
        });

        return $res;

    }

    /**
     * @desc  发送html的邮件信息
     * @param $viewData
     * @param $emailData
     */
    public static function sendHtmlEmail($viewData, $emailData)
    {

        $res = Mail::send($viewData['url'],$viewData['assign'],function ($message) use($emailData){
            $to = $emailData['email_address'];
            $message->to($to)->subject($emailData['subject']);
        });

        return $res;
    }


    /**
     * @desc  设置激活码
     * @param $username
     * @param $email
     */
    public static function createActiveCode($username, $email)
    {

        //  随机六位数激活码
        $rand = rand(100000,999999);
        //  拼接好key
        $key = "FORGET_".$username."_".$email;

        //  存放于rides中
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        //  30分钟有效日期
        $redis->setex($key,1800,$rand);

        //  返回
        return $rand;

    }


}