<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

    Route::post('api/:version/token/user', 'api/:version.Token/getToken');

    
    /***************
     *    User     *
     ***************/
    Route::get('api/:version/user','api/:version.User/User');
    Route::post('api/:version/user','api/:version.User/getUser');
