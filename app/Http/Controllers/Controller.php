<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    //  定义常量分页数
    const
        PAGE_SIZE = 5,
        END       = true;


    /**
     * @desc  删除_token的值
     * @param array $params
     * @return bool
     */
    public function delToken(array $params)
    {

        //  _token为空返回false
        if(!isset($params['_token'])){
            return false;
        }

        //  删除_token
        unset($params['_token']);

        //  返回
        return $params;

    }

    /**
     * @desc  保存数据，此方法可用于添加和修改
     * @param $object
     * @param $params
     * @return bool
     */
    public function storeData($object, $params)
    {

        //  传参为空返回false
        if(empty($params)){
            return false;
        }

        //  循环赋值
        foreach($params as $key => $value){
            $object -> $key = $value;
        }

        //  执行添加
        return $object->save();

    }

    /**
     * @desc  保存数据并获取id（单条）
     * @param $object
     * @param $params
     * @return mixed
     */
    public function storeDataGetId($object, $params)
    {

        return $object->insertGetId($params);

    }

    /**
     * @desc  多条添加
     * @param $object
     * @param $params
     * @return mixed
     */
    public function storeDataMany($object, $params)
    {

        return $object->insert($params);

    }


    /**
     * @desc  通过id获取该数据（默认为主键id）
     * @param $object
     * @param $id
     * @param string $key
     */
    public function getDataInfo($object, $id, $key="id",$fields="*")
    {

        //  没有id返回false
        if(empty($id)){
            return false;
        }

        //  通过id获取一条数据
        $info = $object->select($fields)->where($key, $id)->first();

        //  返回查到的数据
        return $info;

    }


    /**
     * @desc  通过where条件查询一条记录
     * @param $object
     * @param array $where
     * @return mixed
     */
    public function getDataInfoByWhere($object, $where=[])
    {

        $info = $object->where($where)->first();

        return $info;

    }

    /**
     * @desc  获取没有分页的数据列表
     * @param $object
     * @param array $where
     * @return mixed
     */
    public function getDataList($object, $where = [])
    {

        $list = $object->where($where)->get()->toArray();

        return $list;

    }


    /**
     * @desc  获取限制输出的条数
     * @param $object
     * @param int $limit
     * @param array $where
     * @return mixed
     */
    public function getLimitDataList($object, $limit = 5, $where=[])
    {

        $list = $object->where($where)->limit($limit)->get()->toArray();

        return $list;

    }

    /**
     * @desc  获取带有分页的数据列表
     * @param $object
     * @param array $where
     * @return mixed
     */
    public function getPageList($object, $where = [])
    {

        return  $object->where($where)->paginate(self::PAGE_SIZE);

    }

    /**
     * @desc  删除公共方法
     * @param $object
     * @param $id
     * @param string $key
     */
    public function delData($object, $id, $key="id")
    {

        //  执行删除
        return $object->where($key,$id)->delete();

    }


    /**
     * @desc  结构返回json的格式数据
     * @param array $data
     */
    public function returnJson($data = [])
    {

        if(!headers_sent()){
            header(sprintf('%s:%s','Content-Type','application/json'));
        }
        exit(json_encode($data));

    }

}
