<?php

namespace app\api\service;

use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    /*
     * 拼接完整的 url 路径
     * 在执行主方法前，构造函数会先被执行
     */
    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
            $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    /*
     *  访问 url
     *  将获得到的 openid 和 session_key 转化为一个数组
     *  判断一下，是否获取到了 openid 和 session_key ，如果为空，抛出一个异常
     *  判断是否存在 errcode 的键名，存在的话抛出一个异常
     *  拿到openid 和 session_key的话调用 grantToken方法
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key ٩(๑>◡<๑)۶ 及openID时异常');
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                return $this->grantToken($wxResult);
            }
        }
    }

    /*
     *  抛出一个异常
     */
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }

    /*
     *  @uid = userID
     *  拿到 openid
     *  去数据库检查一下这个 openid 是否存在
     *  如果存在，则不处理
     *  如果不存在，那么新增一条记录
     *  同时生成令牌、准备缓存、写入缓存
     *  并把令牌返回到客户端
     */
    private function grantToken($wxResult)
    {
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user) {
            $uid = $user->id;
        } else {
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCacheValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    /*
     *  新增一条 user 记录
     */
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    /*
     *  获取用户身份权限
     *  准备缓存数据
     */
    private function prepareCacheValue($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;

        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }

    /*
     *  获取md5加密过后的一组字符串
     *  将缓存数据转为 json 字符串
     *  设置令牌有效时间
     *  验证前面步是否存在
     *  如果不存在则抛出一个异常
     *  否则把 key 返回回去
     */
    private function saveToCache($cachedValue)
    {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_time = config('setting.token_expire_time');

        $request = cache($key, $value, $expire_time);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }
}