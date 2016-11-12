<?php

/*
 |--------------------------------------------------------------------------
 |应用程序路径
 |--------------------------------------------------------------------------
 |
 | 在这里,您可以注册应用程序的所有航线。
 |这是一个微风。只是告诉Laravel uri应该回应和给它控制器调用请求URI。
 |
 */

Route::get('/', function () {
    return view('welcome');
});
Route::get('login', 'Login\LoginController@index');
//微信接口
Route::get('weixin/api', 'Weichat\WeichatController@index');
Route::post('weixin/api', 'Weichat\WeichatController@responseMsg');
Route::any('wxpayCallback', 'Weichat\WxpayApiController@wxpayCallback');
Route::filter('weixin', function()
{
    // 获取到微信请求里包含的几项内容
    $signature = Input::get('signature');
    $timestamp = Input::get('timestamp');
    $nonce     = Input::get('nonce');
    // token，在微信公众后台设置 
    $token = 'yuelaowai';
    //  做出回应格式signature
    $our_signature = array($token, $timestamp, $nonce);
    sort($our_signature, SORT_STRING);
    $our_signature = implode($our_signature);
    $our_signature = sha1($our_signature);

    // 用自己的 signature 去跟请求里的 signature 对比
    if ($our_signature != $signature) {
        return false;
    }
});

//------------------后台页面路由航向------------------------------------------------------------------------------------+
Route::group(['prefix'=>'admin', 'namespace' => 'Admin'], function() {   //登陆
    Route::get('login', 'Login\LoginController@index');
    Route::post('loginp', 'Login\LoginController@loginpost');
    Route::get('logout', 'Login\LoginController@Logout');


//------------------后台登录验证群组---------------------
    Route::group(['middleware' => 'auth'], function () {
        Route::get('index', 'AdminController@index');
        Route::get('poweredit', 'Power\PowerController@index');//后台权限入口
        Route::post('powerpost', 'Power\PowerController@powerpost');//权限修改
        


    });
});