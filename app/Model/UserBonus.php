<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserBonus extends Model
{

    //  红包记录表
    protected $table = "jy_user_bonus";

    //  维护时间字段
    public $timestamps = true;

    /**
     * @desc  红包的发送记录 用户和红包表
     * @param array $where
     */
    public function getSendRecord($where = [])
    {

        $records = self::select('jy_user_bonus.id','username','phone','bonus_name','start_time','end_time','jy_user_bonus.status')
                       ->leftJoin('jy_bonus','jy_bonus.id','=','jy_user_bonus.bonus_id')
                       ->leftJoin('jy_user','jy_user.id','=','jy_user_bonus.user_id')
                       ->where($where)
                       ->orderBy('jy_user_bonus.id','desc')
                       ->paginate(5);

        return $records;

    }


    /**
     * @desc  通过用户uid获取记录列表
     * @param $userId
     * @param array $where
     */
    public function getRecordByUid($userId, $where = [])
    {

        $records = self::select('bonus_name','money','min_money','start_time','end_time')
                       ->leftJoin('jy_bonus','jy_bonus.id','=','jy_user_bonus.bonus_id')
                       ->where('user_id',$userId)
                       ->where($where)
                       ->orderBy('jy_user_bonus.id','desc')
                       ->paginate(5);

        return $records;

    }


    /**
     * @desc  执行红包发送的操作
     * @param $userIds
     * @param $bonusId
     * @param int $expires
     */
    public function sendBonusMany($userIds, $bonusId, $expires = 7)
    {

        $bonusInfo = [];

        //  重新赋值
        foreach($userIds as $key => $value){

            $bonusInfo[] = [
                'user_id' => $value,
                'bonus_id' => $bonusId,
                'start_time' => date("Y-m-d H:i:s"),
                'end_time' => date("Y-m-d H:i:s",strtotime('+ '.$expires.'days')),
            ];

        }

        return self::insert($bonusInfo);

    }


}
