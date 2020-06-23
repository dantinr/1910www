<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/info',function(){
    phpinfo();
});

Route::get('/test/hello','TestController@hello');
Route::get('/test/redis1','TestController@redis1');

//商品
Route::get('/goods/detail','Goods\GoodsController@detail');     //商品详情



Route::get('/user/reg','User\IndexController@reg');     //前台用户注册
Route::post('/user/reg','User\IndexController@regDo');  //后台用户注册
Route::get('/user/login','User\IndexController@login');  //前台用户登录
Route::post('/user/login','User\IndexController@loginDo');  //用户登录逻辑处理

Route::get('/user/center','User\IndexController@center');  //用户中心



