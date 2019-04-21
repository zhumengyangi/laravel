<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoShopCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto_shop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '商品自动上架功能';

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

        //  所有未上架的商品，并且上架时间小于当前时间
        $goods = \DB::table('jy_goods')
                    ->where('is_shop',2)
                    ->where('shop_time','<=',date("Y-m-d H:i:s"))
                    ->get();

        if(empty($goods)){
            \Log::info('没有可上架的商品');
            return false;
        }

        //  组装id统一处理
        $articleIds = [];

        //  循环拿出id
        foreach ($goods as $key => $value){
        $goodIds[] = $value->id;
    }


        try{
            //  进行修改
            \DB::table('jy_goods')->whereIn('id', $goodIds)->update(['is_shop'=>1]);

            //  写入日志
            \Log::info('商品自动上架成功');

        }catch (\Exception $e){

            //  写入日志
            \Log::error('商品自动上架失败'.$e->getMessage());

        }

    }
}
