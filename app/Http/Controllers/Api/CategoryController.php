<?php

namespace App\Http\Controllers\Api;

use App\Model\Category;
use App\Model\Novel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{


    /**
     * @desc  获取分类列表接口
     * @return string
     */
    public function getCategory()
    {

        //  实例化
        $category = new Category();

        //  调取该方法
        $cList = $category->getCategory();

        //  返回数据
        $return = [
          'code' => 2000,
          'msg'  => '获取分类的接口',
          'data' => $cList
        ];

        return json_encode($return);

    }


    /**
     * @desc 获取小说的分类列表
     * @return string
     */
    public function getCategoryNovel(Request $request)
    {

        //  获取对应的id
        $cId = $request->input('c_id',1);

        //  实例化
        $category = new Novel();

        //  调取该方法
        $list = $category->getNovelByCid($cId);

        //  返回数据
        $return = [
            'code' => 2000,
            'msg'  => '获取小说的分类列表',
            'data' => $list
        ];

        return json_encode($return);

    }


}
