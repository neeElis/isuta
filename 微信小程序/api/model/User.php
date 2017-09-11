<?php


namespace app\api\model;


class User extends BaseModel
{
    public function user(){
        return $this->hasOne('UserInfo','user_id', 'id');
    }

    public static function getByOpenID($openid){
        $user = self::where('openid', '=', $openid)
            ->find();
        return $user;
    }
}