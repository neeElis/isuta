<?php


namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    /*
     *  $code Http 状态码，例如：404,200,201
     *  $msg 错误具体信息
     *  $errorCode 自定义错误码
     */
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}