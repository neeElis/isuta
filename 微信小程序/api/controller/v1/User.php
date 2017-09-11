<?php

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\success\SuccessMessage;
use app\api\validate\UserValidate;
use app\lib\exception\UserException;

class User{

    
    public function User(){
        $uid = TokenService::getCurrentUid();
        $user = UserModel::where('user_id', $uid)
            ->find();
        if(!$user){
            throw new UserException([
                'msg' => '用户不存在',
                'errorCode' => 60001
            ]);
        }
        return $user;
    }

    public function getUser(){
        $data = new UserValidate();
        $data -> goCheck();

        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);

        if(!$user){
            throw new UserException();
        }

        $dataArray = $data->getDataByRule(input('post.'));

        $userInfo = $user->user;
        if(!$userInfo){
            $user->user()->save($dataArray);
        }
        else{
            $user->user->save($dataArray);
        }
        return json(new SuccessMessage(), 201);
    }
}