<?php
namespace App\Tools;

use Excel;
error_reporting( E_ALL&~E_NOTICE);

/**
 * @desc 文件批量导入导出的功能
 * Class ToolsExcel
 * @package App\Tools
 */
class ToolsExcel
{

    /**
     * @desc  excel文件的导入
     * @param $files
     * @return bool
     */
    public static function import($files)
    {

        //  文件不能为空
        if(empty($files)){
            return false;
        }

        //  执行导入功能
        $data = Excel::load($files->path(), function ($reader){
        })->toArray();

        //  返回数据
        return $data;

    }


    /**
     * @desc  文件导出操作
     * @param $exportData
     */
    public static function exportData($exportData, $title="商品数据")
    {

        //  文件不能为空
        if(empty($exportData)){
            return false;
        }

        /*
	    * 如果你要导出csv或者xlsx文件，只需将 export 方法中的参数改成csv或xlsx即可。
	    * 如果还要将该Excel文件保存到服务器上，可以使用 store 方法：
	    */
        Excel::create(iconv('UTF-8', 'GBK', date("YmdHis").$title),function($excel) use ($exportData){
            $excel->sheet('goods', function($sheet) use ($exportData){
                $sheet->rows($exportData);
            });
        })->export('xls');


    }


}