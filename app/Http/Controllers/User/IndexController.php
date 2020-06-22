<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        echo '<pre>';print_r($_POST);echo '</pre>';
    }

    /**
     * 用户登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('user.login');
    }

    public function loginDo()
    {
        echo '<pre>';print_r($_POST);echo '</pre>';
    }
}
