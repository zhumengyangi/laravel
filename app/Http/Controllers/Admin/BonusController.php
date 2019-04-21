<?php

namespace App\Http\Controllers\Admin;

use App\Model\Bonus;
use App\Model\Member;
use App\Model\Region;
use App\Model\UserBonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BonusController extends Controller
{

    /**
     * @desc  红包列表
     */
    public function list()
    {

        //  实例化
        $bonus = new Bonus();

        //  获取分页数据
        $assign['bonus_list'] = $this->getPageList($bonus);

        //  返回
        return view('admin.bonus.list', $assign);

    }

    /**
     * @desc 红包添加
     */
    public function addBonus()
    {

        //  返回
        return view('admin.bonus.add');

    }


    /**
     * @desc  执行红包添加
     * @param Region $region
     */
    public function doAddBonus(Request $request)
    {

        //  获取全部数据
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  实例化
        $bonus = new Bonus();

        //  添加
        $res = $this->storeData($bonus, $params);



        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','添加红包失败');
        }

        //  返回
        return redirect('/admin/bonus/list/');

    }


    /**
     * @desc  发送红包
     * @param $bonusId
     */
    public function sendBonus($bonusId)
    {

        //  实例化
        $bonus = new Bonus();

        //  获取数据
        $assign['bonus_info'] = $this->getDataInfo($bonus, $bonusId);

        //  返回
        return view('admin.bonus.send',$assign);

    }

    public function doSendBonus(Request $request)
    {

        $params = $request->all();

        $params = $this->delToken($params);

        $user = new Member();
        $userInfo = $this->getDataInfo($user, $params['phone'],'phone');

        if(empty($userInfo)){
            return redirect()->back()->with('msg','用户不存在,发送红包失败');
        }

        //  用户红包的数据
        $userBonusData = [
                'user_id'    => $userInfo->id,
                'bonus_id'   => $params['bonus_id'],
                'start_time' => date("Y-m-d H:i:s"),
                'end_time'   => date("Y-m-d H:i:s",strtotime("+ ".$params['expires']." days")),//   多少天
        ];

        //  实例化
        $userBonus = new UserBonus();

        //  添加
        $res = $this->storeData($userBonus, $userBonusData);

        //  添加失败
        if($res){
            return redirect()->back()->with('msg','红包发送记录');
        }

        //  返回
        return redirect('/admin/user/bonus/list');

    }


    /**
     * @desc  红包领取记录
     */
    public function userBonusList()
    {

        //  实例化
        $userBonus = new UserBonus();

        //  红包的发送记录
        $assign['user_bonus'] = $userBonus->getSendRecord();

        //  返回
        return view('admin.bonus.userBonus',$assign);

    }


}
