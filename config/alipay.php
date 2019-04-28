<?php

//  支付宝的支付信息
return [
    'app-id' => '2016100100638398',//你创建应用的APPID
    'notify_url' => env('APP_URL').'/api/notify/url',//laravel_web,//异步回调地址
    'return_url' => env('APP_URL').'/api/return/url',//同步回调地址
    'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgUz5crevYIVkCDd38r+4NrqK5hgKwvSi/bG0B5fkAQBUWViT/4Zi/0ApZ9rb106R8wfitO1yijMbrcMRZ0p/0a8Kwz+Lc43Qm2TjSVGlZ7VDMDQO0hhdBwNZnCES5m6KHdv+PpUCbyprL8SDpsSK/pH/3TyrlRyGecnkgBdyrIo39lA+X99x8DQAlXZ2C5A0RDxvfcB3bGug1PlqGOuqrKwO/kcQtIg8ZDSrUZjx2KPdTdZa557BeydiqxgOsN6P/Z0w7lnjQM02taliwdVWKk/89yP8KMUFR3n2oLG9HW825LZ2T8911GAcmb+kB6ljPvJn181NoabFJc45PScJrQIDAQAB',//是支付宝公钥，不是应用公钥,  公钥要写成一行,不要换行
    'private_key' => 'MIIEpAIBAAKCAQEAxOcKSoSt3Lp/hm6G8z6EEvVYBs4pvjd4MhFsFfBSD79e1cIrCEnhUB2egzfApZmkdDOy3MDxVhJ9Pexc4nzb6YaQEVfi94T29nT7tE1Wn5+8JpSmaftCGXxMOHGWO1Ay6p5K+hU2NTAC3qcaTRO/Lw1hfV5GMdbbG7jZCVQYooM8NOXMZBhVhF/9xUJ9CsZyMO+d+SC0ddwd+/jtHDJ/1bRVewwAHFUGAT1JzklkGGY0E5IcQ0+fDxXJfmHP80AAVk53kAkjtvp2iub9tOd46W1UjfdaaT1lOskm5TpXUqarlYJ7MEA5E7MP339hrOvDqa3VMTxQsewk+E01HycsYwIDAQABAoIBAGOmBZhG43J00/slzpglyFOL5Nu01H1WtzaniIrlz7H2iLknhf2w3h1ckA8aVR25zveb/mYoIzsLKmT/TNa2l9jsZ9bPIqdfnFiIRaYvCarI/UCNNFcoeIBLXoumOICWCt6f85PyzjdGr/0clnvxhWz7mZ4H6wBoGwVBnF1TORBs8yJFGPVUrDIiBIlsc91TfXnkd4dAVqY+l6Z7CC/dg1iSQcGq7KvVRGYYX6i5LFvVD7GTg3MUGxU44WyQ0OuIZn/DOrolAaL4qJltHfk5VRBzlrRCF9YGMNev9jakaYcAfZL28mdU6pzlhcOttDmadGz+X3zQlqE65p6dpeGLKwECgYEA/u/uq56SqBz3/jeO4HOIGFY5fbnrYsCZ46ZIQoSqmT+RFVyFXPPA9Z6xdIQcTGwbXv640xqvg4UoKMupMlP+P96yYyssBFinZ+0H1t+9VDxYaNu2zNHleZ5OTeLwr13d4S4KJv2MDe1ahCKYHTLBUW0CzsbIrX2GEZletdXnTOMCgYEAxbksbC+VzBoaiaxGVKnlfzl05rkVrIzrfO30qU3Ae8jj1x/2yt9EtUnJd6fbaCN0igWMkEPKUaKkqNxiQ9J7bpp39gDZa1mFcGzQo0aSBkhB5yqtkZSsMS/X4TWEtYmeziDzHLkSrKr8zmK/XQ7tXpEWRyBccKBsSFLVq656OoECgYEArzZTwJvhi60tPAkPJ2//z0ojhSdsgZiBq5Yy/5SSc3Tez+GgvvvAWI9SwxOxJ0z9mtJSULR8gFXZ/f/LL43OpjL2Q3X+cJZFqAWeZ62qP+Shlp+7CRYRMv78LkeUx9IploO/8oA7665/kWT9SsQBXWcgkODKN4KXzCyujSaQK6UCgYBq78Sj2nSlB7U0opUDM0QpM/US2CVtxmWSkswRSTkCedsoPWRnwqtRdU/eRE25G/vqrieg2tkwn8t4fIE090DEyAx7Y2gz1B4EeQW6WO+fMDNciEuJgiRDEgIrDpvw5zaZe16hOUNtWakTInsnJGyjCctHuqvkSg9mak6f5OS8gQKBgQCYp3n3FJS8+k/3kf+10rDiU/rmj8QTrWISFEr1rv8oDnNOfKlLyLLIJ6UAxZSnQbEurSlbf/iKHKMebgEwRvvVaXJpj0h9rG/EqlBTX9it2T+ugXwrgbUgtz62HZC7Je6n79goOKFVdrUOVPTTqU3D/2JT2XD1hFhFiD+xoUvH9Q==',//密钥,密钥要写成一行,不要换行
    'log' => [ // optional
        'file' => storage_path('log/alipay.log'),
        'level' => 'info',// 建议生产环境等级调整为 info，开发环境为 debug
        'type'  => 'daily',// optional, 可选 daily.
        'max_file' => 30,// optional, 当 type 为 daily 时有效，默认 30 天
    ],
    'http' => [// optional
        'timeout' => 5.0,
        'connect_timeout' => 5.0,
        // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
    ],
    'mode' => 'dev',// optional,设置此参数，将进入沙箱模式
];