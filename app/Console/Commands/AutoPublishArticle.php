<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoPublishArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto_publish_article';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动发布文章';

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

        //  所有未发布的文章，并且发布时间小于当前时间
        $acticle = \DB::table('jy_acticle')
                      ->where('satatus','<',3)
                      ->where('publish_at','<=',date("Y-m-d H:i:s"))
                      ->get();

        if(empty($acticle)){
            \Log::info('没有可发布的文章');
            return false;
        }

        //  组装id统一处理
        $articleIds = [];

        //  循环拿出id
        foreach ($acticle as $key => $value){
            $articleIds[] = $value->id;
        }


        try{
            //  进行修改
            \DB::table('jy_article')->whereIn('id', $articleIds)->update(['status'=>3]);

            //  写入日志
            \Log::info('文章自动发布成功');

        }catch (\Exception $e){

            //  写入日志
            \Log::error('文章自动发布失败'.$e->getMessage());

        }

    }
}
