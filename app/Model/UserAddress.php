<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{

    //  用户地址信息表
    protected $table = "jy_user_address";

    //  维护时间字段
    public $timestamps = true;

}
