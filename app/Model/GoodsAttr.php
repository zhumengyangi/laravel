<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsAttr extends Model
{

    //  定义常量分页数
    const
        PAGE_SIZE = 5,
        INPUT_HANDEL = 1,// 手动录入
        INPUT_LIST   = 2,// 列表录入
        END       = true;

    //  链接表名
    protected $table = "jy_goods_attr";

    //  不维护时间戳
    public $timestamps = false;


    /**
     * @desc  获取属性列表   根据商品属性和商品分类查询
     * @param array $where
     * @return mixed
     */
    public function getList($where = [])
    {

        $list = self::select('jy_goods_attr.id','attr_name','jy_goods_type.type_name','input_type','attr_value','jy_goods_attr.status')
                    ->leftJoin('jy_goods_type','jy_goods_attr.cate_id','=','jy_goods_type.id')
                    ->where($where)
                    ->paginate(self::PAGE_SIZE);

        return $list;

    }


    /**
     * @desc  获取手动录入的属性
     * @param array $where
     * @return array
     */
    public function getAttrHandle($where=[])
    {

        return self::select('id','attr_name')
                   ->where($where)
                   ->where('input_type',self::INPUT_HANDEL)
                   ->get()
                   ->toArray();

    }


    /**
     * @desc  获取列表选取的属性
     * @param array $where
     * @return array
     */
    public function getAttrList($where=[])
    {

        return self::select('id','attr_name')
            ->where($where)
            ->where('input_type',self::INPUT_LIST)
            ->get()
            ->toArray();

    }


    /**
     * @desc  获取sku属性列表的值
     * @param $id
     * @return Model|null|static
     */
    public function getAttrValue($id)
    {

        return self::select('attr_value')
                   ->where('id',$id)
                   ->first();

    }


}
