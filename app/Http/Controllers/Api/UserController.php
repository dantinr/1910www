<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Model\TokenModel;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{

    /**
     * 用户注册
     * @param Request $request
     */
    public function reg(Request $request)
    {

        $pass1 = $request->input('pass1');
        $pass2 = $request->input('pass2');
        $name = $request->input('name');
        $email = $request->input('email');

        //密码长度是否大于6
        $len = strlen($pass1);
        if($len<6){
           $response = [
               'errno'  => 50001,
               'msg'    => '密码长度必须大于6'
           ];
           return $response;
        }

        //两次密码是否一致
        if($pass1 != $pass2)
        {
            $response = [
                'errno'  => 50002,
                'msg'    => '两次输入的密码不一致'
            ];
            return $response;
        }

        //检查用户是否已存在
        $u = UserModel::where(['user_name'=>$name])->first();
        if($u){
            $response = [
                'errno'  => 50003,
                'msg'    => $name . " 用户名已存在"
            ];
            return $response;
        }

        //检查Email是否已存在
        $u = UserModel::where(['email'=>$email])->first();
        if($u){
            $response = [
                'errno'  => 50004,
                'msg'    => $email . " Email已存在"
            ];
            return $response;
        }


        //生成密码
        $pass = password_hash($pass1,PASSWORD_BCRYPT);

        //验证通过 生成用户记录
        $data = [
            'user_name'     => $name,
            'email'         => $email,
            'password'      => $pass,
            'reg_time'      => time()
        ];

        $res = UserModel::insert($data);
        if($res)
        {
            $response = [
                'errno'  => 0,
                'msg'    => "注册成功"
            ];

        }else{
            $response = [
                'errno'  => 50005,
                'msg'    => "注册失败"
            ];
        }

        return $response;

    }


    /**
     * 登录
     * @param Request $request
     */
    public function login(Request $request)
    {
        $name = $request->input('name');
        $pass = $request->input('pass');

        //验证登录信息
        $u = UserModel::where(['user_name'=>$name])->first();

        //验证密码
        $res = password_verify($pass,$u->password);

        if($res)
        {
            //生成token
            $str = $u->user_id . $u->user_name . time();
            $token = substr(md5($str),10,16) . substr(md5($str),0,10);

            //将token保存在redis中
            Redis::set($token,$u->user_id);       // sldkfjslkfj 1234

            $response = [
                'errno' => 0,
                'msg'   => 'ok',
                'token' => $token
            ];

        }else{

            $response = [
                'errno' => 50006,
                'msg'   => '用户名与密码不一致,请重新登录',
            ];

        }

        return $response;

    }


    /**
     * 个人中心
     */
    public function center()
    {
        //判断用户是否登录 ,判断是否有 uid 字段
        $token = $_GET['token'];
        //检查token是否有效
        $uid = Redis::get($token);

        if($uid)
        {
            $user_info = UserModel::find($uid);
            //已登录
            echo $user_info->user_name . " 欢迎来到个人中心";
        }else{
            //未登录
            echo "请登录";
        }

    }



}
