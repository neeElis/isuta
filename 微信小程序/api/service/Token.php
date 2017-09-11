<?php


namespace app\api\service;

use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    /*
     *  随机生成一组15位的字符串
     *  获得时间戳
     *  @salt
     *  用三组字符串进行 md5 加密
     */
    public static function generateToken()
    {
        $randChars = getRandChar(50000);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token_salt');

        return md5($randChars . $timestamp . $salt);
    }

    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /*
     *  获取http头信息中的 token
     *  调用 tp5 check下的get方法验证 $token
     *  首先要判断的是是否获取到了token 没获取到token的话抛出一个异常
     *  然后判断 token是不是一个数组，不是一个数组的话，将它转换为数组
     *  在判断 token中是否存在 $key 键, 如果存在将 $key 返回 
     *  不存在则抛出一个异常
     */
    public static function getCurrentTokenVar($key){
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }
        else{
            if(!is_array($vars)){
                $vars = json_decode($vars, true);
            }
            if(array_key_exists($key, $vars)){
                return $vars[$key];
            }
            else{
                throw new Exception('(；´д｀)ゞ获取的Token变量不存在');
            }
        }
    }
}