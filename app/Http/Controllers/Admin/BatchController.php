<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\BonusBatchJob;
use App\Model\Batch;
use App\Model\Bonus;
use App\Tools\ToolsAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BatchController extends Controller
{

    /**
     *@desc  批次的列表
     */
    public function list()
    {

        //  实例化
        $batch = new Batch();

        //  查出分页数据
        $assign['batch_list'] = $this->getPageList($batch);

        //  返回
        return view('admin.batch.list',$assign);

    }


    /**
     * @desc  批次添加页面
     */
    public function add()
    {

        //  返回
        return view('admin.batch.add');

    }


    /**
     * @desc  批次执行添加
     * @param Request $request
     */
    public function store(Request $request)
    {

        //  获取所有数据
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  文件上传
        $params['file_path'] = ToolsAdmin::uploadFile($params['file_path'], false);
        //  状态为正常的
        $params['status'] = 2;

        //  实例化
        $batch = new Batch();

        //  添加数据
        $res = $this->storeData($batch, $params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','添加批次失败');
        }

        //  返回
        return redirect('/admin/batch/list');

    }


    /**
     * @desc  执行批次
     * @param $id
     */
    public function doBatch($id)
    {

        //  实例化
        $batch = new Batch();

        //  获取数据并转成数组
        $batchInfo = $this->getDataInfo($batch, $id)->toArray();

        //  获取文件内容
        $fileContent = file_get_contents(substr($batchInfo['file_path'], 1));

        //  分割数组
        $arr = explode("\r\n", $fileContent);


        //  只要发送红包的批次
        if($batchInfo['type'] == 1){

            //  把读出来文件内容进行数组的拆分成两个一组的
            $arr = array_chunk($arr, 2);

            //  红包的id
            $bonusId = $batchInfo['content'];

            //  实例化
            $bonus = new Bonus();

            //  通过红包id 获取所有红包的数据
            $bonusInfo = $this->getDataInfo($bonus, $bonusId);

//            dd($batchInfo);

            //  循环
           foreach ($arr as $key => $value){

               //  重新组装数据
               $data = [
                   'user_id' => $value,
                   'bonus_id' => $bonusId,
                   'expires'  => $bonusInfo->expires
               ];

               //  实例化队列任务类
               $job = new BonusBatchJob($data);

               //  执行任务分发
               dispatch($job);

           }

        }

        //  通过id 查询修改的数据
        $batch = Batch::find($id);

        //  然后修改批次状态为已处理
        $this->storeData($batch, ['status' => 3]);

        //  返回
        return redirect('/admin/batch/list');

    }




}
