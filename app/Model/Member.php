<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{

    //  会员表
    protected $table = "jy_user";

    //  维护时间
    public $timestamps = true;

    /**
     * @desc 获取详情 通过会员表和会员详情表查取
     * @param $id
     * @return Model|null|static
     */
    public function getInfo($id)
    {

        $info = self::select('*')
                    ->leftJoin('jy_user_info','jy_user_info.user_id','=','jy_user.id')
                    ->where('jy_user.id',$id)
                    ->first();

        return $info;

    }


}
