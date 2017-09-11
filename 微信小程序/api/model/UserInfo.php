<?php

namespace app\api\model;

class UserInfo extends BaseModel{
    protected $hidden = ['id', 'delete_time', 'user_id'];
}