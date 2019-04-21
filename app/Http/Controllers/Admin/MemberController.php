<?php

namespace App\Http\Controllers\Admin;

use App\Model\Member;
use App\Model\UserBonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{

    /**
     * @desc  获取会员列表
     */
    public function list()
    {

        //  实例化
        $member = new Member();

        //  返回数据
        $assign['members'] = $this->getPageList($member);

        //  返回
        return view('admin.member.list',$assign);

    }

    /**
     * @desc  会员详情
     * @param $id
     */
    public function detail($id)
    {

        //  实例化
        $member = new Member();
        $userBonus = new UserBonus();

        //  获取详情
        $assign['info'] = $member->getInfo($id);
        $assign['bonus_list'] = $userBonus->getRecordByUid($id);

        //  返回
        return view('admin.member.detail',$assign);

    }


}
