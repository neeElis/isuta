<?php

namespace app\lib\exception;

class ParameterException extends BaseException{
    public $code = 400;
    public $msg = '￣へ￣参数错误';
    public $errorCode = 10000;
}