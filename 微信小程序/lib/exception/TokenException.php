<?php


namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期 ヽ(ﾟ∀ﾟ)ﾒ(ﾟ∀ﾟ)ﾉ  或无效的Token';
    public $errorCode = 10001 ;
}