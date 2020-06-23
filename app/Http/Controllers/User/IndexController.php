<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;

class IndexController extends Controller
{

    /**
     * 用户注册页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reg()
    {
        return view('user.reg');
    }


    /**
     * 用户注册 逻辑
     * @param Request $request
     */
    public function regDo(Request $request)
    {
        //echo '<pre>';print_r($_POST);echo '</pre>';
        $pass1 = $request->input('pass1');
        $pass2 = $request->input('pass2');
        $name = $request->input('name');
        $email = $request->input('email');

        //密码长度是否大于6
        $len = strlen($pass1);
        if($len<6){
            die("密码长度必须大于6");
        }

        //两次密码是否一致
        if($pass1 != $pass2)
        {
            die("两次输入的密码不一致");
        }

        //检查用户是否已存在
        $u = UserModel::where(['user_name'=>$name])->first();
        if($u){
            die( $name . " 用户名已存在");
        }

        //检查Email是否已存在
        $u = UserModel::where(['email'=>$email])->first();
        if($u){
            die("Email已存在");
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
            echo "注册成功";
        }else{
            echo "注册失败";
        }


    }

    /**
     * 用户登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('user.login');
    }

    /**
     * 用户登录逻辑
     */
    public function loginDo(Request $request)
    {
        $name = $request->input('name');
        $pass = $request->input('pass');

        //echo '用户输入的密码： '. $pass;echo '</br>';

        //验证登录信息
        $u = UserModel::where(['user_name'=>$name])->first();
        //echo '数据库的密码：' .$u->password;echo '</br>';

        //验证密码
        $res = password_verify($pass,$u->password);
        if($res)
        {
            //向客户端设置cookie
            setcookie('uid',$u->user_id,time()+3600,'/');
            setcookie('name',$u->user_name,time()+3600,'/');
            header('Refresh:2;url=/user/center');
            echo "登录成功";
        }else{
            header('Refresh:2;url=/user/login');
            echo "用户名与密码不一致,请重新登录";
        }



    }


    public function center()
    {
        //判断用户是否登录 ,判断是否有 uid 及 name字段
        //echo '<pre>';print_r($_COOKIE);echo '</pre>';
        if(isset($_COOKIE['uid']) && isset($_COOKIE['name']) )
        {
            return view('user.center');
        }else{
            //echo "未登录";die;
            return redirect('/user/login');
        }


    }
}
