<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tools\ToolsEmail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送邮件';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //  获取数据
        $m_nums = \DB::table('jy_user')->count();
        $order_nums = \DB::table('jy_order')->count();
        $goods_nums = \DB::table('jy_goods')->count();

        //  组装要发送的信息
        $viewData = [
            'url' => 'email.data',
            'assign' => [
                'm_nums' => $m_nums,
                'order_nums' => $order_nums,
                'goods_nums' => $goods_nums
            ],
        ];

        //  组装发送者
        $emailData = [
            'subject' => date("Y-m-d").'_数据统计邮件',
            'email_address' => '1784311404@qq.com'
        ];

        try{

            //  发送
            ToolsEmail::sendHtmlEmail($viewData, $emailData);
            //  写入日志
            \Log::info('发送统计邮件成功');

        }catch (\Exception $e){

            //  写入日志
            \Log::error('发送统计邮件成功'.$e->getMessage());

        }

    }
}
