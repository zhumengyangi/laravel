<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserFundHistory extends Model
{

    //  用户资金流水表
    protected $table = "jy_user_fund_history";

    //  维护时间字段
    public $timestamps = true;

}
