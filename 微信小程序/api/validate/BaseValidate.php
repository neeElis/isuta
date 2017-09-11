<?php


namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

/*
 *  获取请求实例
 *  获取传入的参数
 *  进行批量验证
 *  判断是否存在，不存在的话抛出一个异常
 *  存在返回true
 */
class BaseValidate extends Validate
{
    public function goCheck(){
        $request = Request::instance();
        $params = $request->param();

        $result = $this->batch()->check($params);
        if(!$result){
            $e = new ParameterException([
                'msg' => $this->error,
            ]);
            throw $e;
        }
        else{
            return true;
        }
    }

    /*
     *  判断是否为空
     */
    protected function isNotEmpty($value, $rule = '', $data = '', $field = ''){
        if(empty($value)){
            return false;
        }
        else{
            return true;
        }
    }

    /*
     *  判断是否为整数
     */
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = ''){
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function getDataByRule($arrays){
        if(array_key_exists('user_id',$arrays)
          |array_key_exists('uid', $arrays)){
              throw new ParameterException([
                  'msg' => '参数中包含有非法的参数名user_id或者uid'
              ]);
          }
          $newArray = [];
          foreach($this->rule as $key => $value){
              $newArray[$key] = $arrays[$key];
          }
          return $newArray;
    }
}