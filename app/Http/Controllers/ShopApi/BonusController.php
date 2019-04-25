<?php

namespace App\Http\Controllers\ShopApi;

use App\Model\UserBonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BonusController extends Controller
{

    /**
     * @desc  用户中心红包记录
     * @param $userId
     */
    public function userBonusList($userId)
    {

        //  成功接口返回20000
        $return = [
            'code' => 2000,
            'msg'  => '获取用户红包成功'
        ];

        //  实例化
        $userBonus = new UserBonus();

        //  通过用户id获取红包记录
        $bonusList = $userBonus->getRecordByUid($userId);

        //  返回数据
        $return['data'] = $bonusList;

        //  返回
        $this->returnJson($return);

    }

}
