<?php

namespace App\Http\Controllers\Admin;

use App\Model\Region;
use App\Tools\ToolsAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{

    /**
     * @desc  列表页面
     * @param int $fid
     */
    public function list($fid = 0)
    {

        //  实例化
        $region = new Region();

        //  根据fid 查数据
        $assign['region_list'] = $this->getDataList($region, ['f_id' => $fid]);

        //  返回
        return view('admin.region.list',$assign);

    }

    public function add()
    {

        //  实例化
        $region = new Region();

        //  添加数据
        $region = $this->getDataList($region);

        //  创建无限极分类树的结构
        $assign['region_list'] = ToolsAdmin::buildTreeString($region, 0, 0, 'f_id');

        //  返回
        return view('admin.region.add',$assign);

    }

    public function store(Request $request)
    {

        //  获取全部参数
        $params = $request->all();

        //  删除_token
        $params = $this->delToken($params);

        //  实例化
        $region = new Region();

        //  通过f_id获取一条数据
        $info = $this->getDataInfo($region, $params['f_id']);

        //  层级加一所以将level加一
        $params['level'] = $info->level + 1;

        //  添加
        $res = $this->storeData($region, $params);

        //  添加失败
        if(!$res){
            return redirect()->back()->with('msg','添加失败');
        }

        //  成功返回
        return redirect('/admin/region/list/'.$params['f_id']);

    }

    public function del($id)
    {

        //  实例化
        $region = new Region();

        //  通过id获取该数据
        $info = $info = $this->getDataInfo($region, $id);

        //  删除
        $res = $this->delData($region, $id);

        //  成功返回
        return redirect('/admin/region/list/'.$info->f_id);

    }

}
