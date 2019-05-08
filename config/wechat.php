<?php
//  配置微信公众号的信息

return [

    //  开发者id
    'app_id' => "wxa318e9abd6a77661",
    //  开发者秘钥
    'app_secret' => "3b18938032fb924c2d5e1fa13cdf5daa",
    //  token值
    'token' => "zmyweixin",

    //  获取access_token的地址
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=clihttps://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_codeent_credential&appid=%s&secret=%s",

    //  获取自定义菜单接口url地址
    'menu_url' => "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s",

    //  获取网页授权code码的地址
    'wap_code_url' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=test#wechat_redirect',

    //  网页授权的access_token
    'page_access_token_url' => 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code',

    //  获取用户详细信息
    'user_info_url' => 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN',

];