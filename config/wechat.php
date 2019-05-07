<?php
//  配置微信公众号的信息

return [
    'app_id' => "wxa318e9abd6a77661",   //  开发者id
    'app_secret' => "3b18938032fb924c2d5e1fa13cdf5daa", //  开发者秘钥
    'token' => "zmyweixin", //  token值
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",   //  获取access_token的地址
    'menu_url' => "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s",   //  获取自定义菜单接口url地址

];