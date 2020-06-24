<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Http\Request;

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

}
