<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brand;
use App\Model\Category;
use App\Model\Goods;
use App\Model\GoodsGallery;
use App\Model\GoodsType;
use App\Tools\ToolsAdmin;
use App\Tools\ToolsExcel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{

    /**
     * @desc  商品列表页面
     */
    public function list()
    {

        //  返回
        return view('admin.goods.list');

    }


    /**
     * @desc  获取列表数据的接口
     * @param Request $request
     * @return mixed|string
     */
    public function getGoodsData(Request $request)
    {

        //  获取全部数据
        $params = $request->all();


        //  成功返回
        $return = [
            'code' => 2000,
            'msg'  => '获取商品列表数据'
        ];

        //  实例化
        $goods = new Goods();

        //  获取带有分页的列表数据转换成数组
        $data = $this->getPageList($goods)->toArray();


        //  组装数据
        $return['data'] = [
            'list' => $data['data'],
            'current_page' => $data['current_page'],
            'total_page'   => $data['last_page']
        ];


        //  返回
        return json_encode($return);

    }


    /**
     * @desc  修改属性值
     * @param Request $request
     * @return mixed|string
     */
    public function changeAttr(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  成功
        $return = [
            'code' => 2000,
            'msg'  => '修改商品属性成功'
        ];

        //  根据id查到该条数据
        $goods = Goods::find($params['id']);

        //  组装要修改的数据值
        $data = [
            $params['key'] => $params['val'],
        ];

        //  保存数据（修改）
        $res = $this->storeData($goods, $data);

        //  判断是否执行成功
        if(!$res){

            $return = [
                'code' => 4000,
                'msg'  => '修改商品属性失败'
            ];

        }

        //  返回
        return json_encode($return);

    }


    /**
     * @desc  商品添加页面
     */
    public function add()
    {

        //  商品类型数据
        $type = new GoodsType();
        $assign['type_list'] = $this->getDataList($type, ['status' => GoodsType::USE_ABLE]);

        //  商品品牌数据
        $brand = new Brand();
        $assign['brand_list'] = $this->getDataList($brand, ['status' => Brand::USE_ABLE]);

        //  商品分类数据和分类树
        $category = new Category();
        $assign['cate_list'] = $this->getDataList($category, ['status' => Category::USE_ABLE]);
        $assign['cate_list'] = ToolsAdmin::buildTreeString($assign['cate_list'],0,0,'f_id');

        //  默认生成商品货号
        $assign['goods_sn'] = ToolsAdmin::buildGoodsSn();

        //  返回
        return view('admin.goods.add', $assign);

    }


    /**
     * @desc  执行商品添加的操作
     * @param Request $request
     */
    public function store(Request $request)
    {


        //  获取全部的值
        $params = $request->all();

//        dd($params);

        //  相册最大上传限制
        if(!isset($params['gallery']) && count($params['gallery']) >5 ){
            return redirect()->back()->with('msg','已经超过相册上传上线');
        }

        //  删除不用的token
        $params = $this->delToken($params);

        //  拿到相册的数据
        $gallery = $params['gallery'];
        //  添加商品不需要相册信息 所以删除
        unset($params['gallery']);

        try{
            //  开启事务
            DB::beginTransaction();

            //  添加商品信息
            $goods = new Goods();
            $goodsId = $this->storeDataGetId($goods, $params);
            //dd($goodsId);
            //  添加相册的信息

            //  初始化值
            $gallery_data = [];
            //  1、格式化相册的数据
            foreach ($gallery as $key => $value)
            {

                //  判断是否上传了图片
                if(array_key_exists('image_url', $value)){

                    //  上传后的图片地址
                    $value['image_url'] = ToolsAdmin::uploadFile($value['image_url']);
//                    dd($value);
                    //  查找到是谁的商品id
                    //$value['goods_id'] = $params['id'];    //修改前
                    $value['goods_id'] = $goodsId;//    修改后
//                    dd($value);
                    // 重新组装新的数据
                    $gallery_data[$key] = $value;

                }
                  //dd($gallery_data);

            }


            //  2、执行添加的操作
            if(!empty($gallery_data)){

                //  实例化
                $goodsGallery = new GoodsGallery();

                //  执行添加的操作
                $this->storeDataMany($goodsGallery, $gallery_data);

            }

            //  提交事务
            DB::commit();
        }catch(\Exception $e){

            //  事务回滚
            DB::rollBack();

            //  写日志
            \Log::error('商品添加失败'.$e->getMessage());

            //  返回
            return redirect()->back()->with('msg','商品添加失败');

        }

        //  成功跳转
        return redirect('/admin/goods/list');

    }


    /**
     * @desc  商品属性编辑页面
     * @param $id
     */
    public function edit($id)
    {


        //  商品类型数据
        $type = new GoodsType();
        $assign['type_list'] = $this->getDataList($type, ['status' => GoodsType::USE_ABLE]);

        //  商品品牌数据
        $brand = new Brand();
        $assign['brand_list'] = $this->getDataList($brand, ['status' => Brand::USE_ABLE]);

        //  商品分类数据和分类树
        $category = new Category();
        $assign['cate_list'] = $this->getDataList($category, ['status' => Category::USE_ABLE]);
        $assign['cate_list'] = ToolsAdmin::buildTreeString($assign['cate_list'],0,0,'f_id');


        //  实例化
        $goods = new Goods();

        //  通过id获取该数据（默认为主键id）
        $assign['info'] = $this->getDataInfo($goods, $id);

        //  返回
        return view('admin.goods.edit',$assign);

    }



    /**
     * @desc  商品属性执行编辑
     * @param Request $request
     */
    public function doEdit(Request $request)
    {

        //  获取全部的值
        $params = $request->all();

        //  相册最大上传限制
        if(!isset($params['gallery']) && count($params['gallery']) >5 ){
            return redirect()->back()->with('msg','已经超过相册上传上线');
        }

        //  删除不用的token
        $params = $this->delToken($params);

        //  拿到相册的数据
        $gallery = $params['gallery'];
        //  添加商品不需要相册信息 所以删除
        unset($params['gallery']);

        try{
            //  开启事务
            DB::beginTransaction();

            //  根据id查到该条信息
            $goods = Goods::find($params['id']);

            //  保存数据（修改）
            $this->storeData($goods, $params);

            //  添加相册的信息

            //  初始化值
            $gallery_data = [];
            //  1、格式化相册的数据
            foreach ($gallery as $key => $value)
            {

                //  判断是否上传了图片
                if(array_key_exists('image_url', $value)){

                    //  上传后的图片地址
                    $value['image_url'] = ToolsAdmin::uploadFile($value['image_url']);
                    //  查找到是谁的商品id
                    $value['goods_id'] = $params['id'];
                    // 重新组装新的数据
                    $gallery_data[$key] = $value;

                }

            }

            //  2、执行添加的操作
            if(!empty($gallery_data)){

                //  实例化
                $goodsGallery = new GoodsGallery();

                //  执行添加的操作
                $this->storeDataMany($goodsGallery, $gallery_data);

            }

            //  提交事务
            DB::commit();
        }catch(\Exception $e){

            //  事务回滚
            DB::rollBack();

            //  写日志
            \Log::error('商品添加失败'.$e->getMessage());

            //  返回
            return redirect()->back()->with('msg','商品添加失败');

        }

        //  成功跳转
        return redirect('/admin/goods/list');

    }

    /**
     * @desc  执行删除商品
     * @param $id
     */
    public function del($id)
    {

       //   成功返回
       $return = [
           'code' => 2000,
           'msg'  => '商品删除成功'
       ];

        //   实例化
       $goods = new Goods();
       $gallery = new GoodsGallery();


       try{

           //   开始事物
           DB::beginTransaction();

           //   删除商品
           $this->delData($goods, $id);

           //   删除相册
           $this->delData($gallery, $id, 'goods_id');

           //   提交事务
           DB::commit();

       }catch (\Exception $e){

           //   事物回滚
           DB::rollBack();

           //   执行事物
           \Log::error('商品删除失败'.$e->getMessage());

           //   返回错误信息
           $return = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage()
           ];

       }

        return json_encode($return);

    }


    /**
     * @desc  商品批量导入的功能
     */
    public function import()
    {

        //  导出id和goods_name
        $cellData[] = ['id','goods_name','goods_sn'];

        //  实例化
        $goods = new Goods();

        //  获取全部的值
        $data = $this->getDataList($goods);

        //  循环赋值
        foreach($data as $key => $value){

            $cellData[] = [
                $value['id'],$value['goods_name'],$value['goods_sn']
            ];

        }

//        //  导出数据
//        \Excel::create('Excel导出数据',function($excel) use ($cellData){
//
//            $excel->sheet('数据', function($sheet) use ($cellData){
//                $sheet->rows($cellData);
//            });
//
//        })->export('xls');
//
//          dd('success');

        return view('admin.goods.import');

    }


    /**
     * @desc  执行导入的操作  备注：excel时间格式 需要设定为Y-m-d H:i:s
     * @param Request $request
     */
    public function doImport(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  获取要导入的文件
        $files = $params['file_name'];

        //  判断文件的后缀名
        if($files->extension() !="xls" && $files->extension()!="xlsx"){
            return redirect()->back()->with('msg','文件格式不正确,请上传xls或slsx文件');
        }

        //  excel文件的导入
        $data = ToolsExcel::import($files);

        //  实例化
        $goods = new Goods();

        //  设置一个空数组 给新的货号使用
        $goodsData = [];


        //  从新生成商品货号
        foreach ($data as $key => $value) {

            $value['goods_sn'] = ToolsAdmin::buildGoodsSn();

            $goodsData[$key] = $value;
        }


        //  执行多条添加
        $res = $this->storeDataMany($goods, $goodsData);

        //  导入失败
        if(!$res){

            return redirect()->back()->with('msg','导入失败');

        }

        //  返回
        return redirect('/admin/goods/list');

    }


    /**
     * @desc  商品导出的功能
     */
    public function export()
    {

        //  实例化
        $goods = new Goods();

        //  获取全部的值
        $data = $this->getDataList($goods);

        //  导出的数据存放该数组中
        $exportData = [];

        //  excel的head头部 自己想要导出的数据
        $head = ['id','cate_id','goods_name','goods_sn'];

        $exportData[] = ['ID','分类ID','商品名称','商品货号'];

        //  组装打印的数据
        foreach ($data as $key => $value){

            $tmpArr = [];
            foreach ($head as $column){
                $tmpArr[] = $value[$column];
            }

            $exportData[] = $tmpArr;

        }
//        dd($exportData);

        //  文件导出操作
        ToolsExcel::exportData($exportData);

    }

}
