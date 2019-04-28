<?php

namespace App\Http\Controllers\ShopApi;

use App\Model\Member;
use App\Model\MemberInfo;
use App\Model\Region;
use App\Model\UserAddress;
use App\Model\UserFundHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * @desc  获取用户中心用户的详情
     * @param $id
     */
    public function userInfo($id)
    {

        $return = [
            'code' => 2000,
            'msg'  => '获取用户信息成功'
        ];

        //  实例化
        $member = new Member();

        //  获取详情 对象转成数组
        $info = $member->getInfo($id)->toArray();

        //  返回数据
        $return['data'] = $info;

        //  返回
        $this->returnJson($return);

    }


    /**
     * @desc  修改用户信息的操作
     * @param Request $request
     */
    public function userModify(Request $request)
    {

        //  获取全部数据
        $params = $request->all();

        //  成功返回2000
        $return = [
            'code' => 2000,
            'msg'  => '用户信息修改成功'
        ];

        //  多表使用事物
        try{

            //  开始事物
            DB::beginTransaction();

            //  修改信息到user表
            $member = Member::find($params['id']);

            //  修改的数据
            $user = [
                'username' => $params['username']
            ];

            //  保存数据
            $this->storeData($member, $user);

            //  实例化
            $memberInfo = new MemberInfo();

            //  通过id获取该数据（默认为主键id）
            $userInfo = $this->getDataInfo($memberInfo,$params['id'],'user_id');

            //  组装修改的数据
            $info = [
                'email' => $params['email'],
                'sex' => $params['sex'],
                'link_name' => $params['link_name'],
                'link_phone' => $params['link_phone'],
            ];

            //  保存数据
            $this->storeData($userInfo, $info);

            //  提交事务
            DB::commit();

        }catch (\Exception $e){

            //  事务回滚
            DB::rollback();

            //  写日志
            \Log::error('用户中心修改信息失败'.$e->getMessage());

            //  返回错误信息
            $return = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage()
            ];
        }

        //  返回
        $this->returnJson($return);

    }


    /**
     * @desc  用户资金流水接口
     * @param $userId
     */
    public function userFundHistory($userId)
    {

        //  成功返回2000
        $return = [
            'code' => 2000,
            'msg'  => '获取用户资金流水成功'
        ];

        //  实例化
        $funHistory = new UserFundHistory();

        //  获取没有分页的数据
        $fund = $this->getDataList($funHistory, ['user_id' => $userId]);

        //  返回数据
        $return['data'] = $fund;

        //  返回
        $this->returnJson($return);

    }


    /**
     * @desc  获取地址信息
     * @param $fid
     */
    public function getRegion($fid)
    {

        //  成功返回2000
        $return = [
            'code' => 2000,
            'msg'  => '获取地址信息成功'
        ];

        //  实例化
        $region = new Region();

        //  获取没有分页的数据
        $list = $this->getDataList($region, ['f_id' => $fid]);

        //  返回数据
        $return['data'] = $list;

        //  返回
        $this->returnJson($return);

    }


    /**
     * @desc  新增用户收货地址的接口
     * @param Request $request
     */
    public function addUserAddress(Request $request)
    {

        //  成功返回2000
        $return = [
            'code' => 2000,
            'msg'  => '获取地址信息成功'
        ];

        //  获取全部数据
        $params = $request->all();

        //  原生添加数据
        $res = \DB::table('jy_user_address')->insert($params);

        //  失败情况下
        if(!$res){

            $return = [
                'code' => 4000,
                'msg'  => '新增收货地址失败'
            ];

        }

        //  返回数据
        $this->returnJson($return);

    }


    /**
     * @desc  获取用户的地址信息
     * @param $userId
     */
    public function getUserAddress($userId)
    {

        //  实例化
        $userAddress = new UserAddress();

        //  成功返回20000
        $return = [
            'code' => 2000,
            'msg'  => '获取地址信息数据成功'
        ];

        //  实例化
        $region = new Region();

        //  获取数据
        $address = $this->getDataList($userAddress, ['user_id'=>$userId]);

        //  进行循环出来
        foreach ($address as $key => $value){

            //  国家
            $country = $this->getDataInfo($region, $value['country']);
            $address[$key]['country'] = $country->region_name;

            //  省
            $country = $this->getDataInfo($region, $value['country']);
            $address[$key]['country'] = $country->region_name;

            //  市
            $country = $this->getDataInfo($region, $value['country']);
            $address[$key]['country'] = $country->region_name;

            //  区
            $country = $this->getDataInfo($region, $value['country']);
            $address[$key]['country'] = $country->region_name;

        }

        //  返回数据
        $return['data'] = $address;

        //  返回
        $this->returnJson($return);

    }


    /**
     * @desc  设置默认地址
     * @param Request $request
     */
    public function setDefaultAddress(Request $request)
    {

        //  成功调用返回2000
        $return = [
            'code' => 2000,
            'msg'  => '设置默认地址成功'
        ];

        //  获取全部参数
        $params = $request->all();

        //  查到该条数据
        $member = Member::find($params['user_id']);

        //  设置为默认  （保存）
        $res = $this->storeData($member, ['address_id' => $params['id']]);

        //  判断修改成功或失败
        if (!$res) {

            $return = [
                'code' => 4001,
                'msg'  => '设置默认地址失败'
            ];

        }

        //  返回
        $this->returnJson($return);

    }

}
