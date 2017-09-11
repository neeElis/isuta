<?php

namespace app\api\validate;

class UserValidate extends BaseValidate{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'card' => 'require|isNotEmpty',
        'mobile' => 'require|isNotEmpty',
        'bank' => 'require|isNotEmpty',
    ];
}