<?php


namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = 'ヾ(◍°∇°◍)ﾉﾞ微信服务器接口调用失败';
    public $errorCode = 999;
}