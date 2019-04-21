<?php

namespace App\Http\Controllers\Api;

use App\Model\Novel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{

    public function getSearchList(Request $request)
    {

        //  获取name
        $name = $request->input('name');

        //  实例化
        $list = new Novel();

        //  调取该方法
        $list = $list->getNovelByName($name);

        //  总条数
        $totalNum = count($list);

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '成功',
            'data' => [
                'list' => $list,
                'total_num' => $totalNum
            ]
        ];

        return json_encode($return);
    }

}
