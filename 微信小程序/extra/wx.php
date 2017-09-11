<?php

return [
    'app_id' => '微信小程序appid',
    'app_secret' => '微信小程序secret',
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" .
                    "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code"
];