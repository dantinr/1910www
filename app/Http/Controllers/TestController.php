<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{

    public function hello()
    {
        echo __METHOD__;echo '</br>';
        echo date('Y-m-d H:i:s');
    }

    //Redis测试
    public function redis1()
    {
        $key = 'name2';
        $val1 = Redis::get($key);           // get name1
        var_dump($val1);echo '</br>';
        echo '$val1: '. $val1;
    }


    public function test1()
    {
        $data = [
            'name'  => 'zhangsan',
            'email' => 'zhangsan@qq.com'
        ];

        return $data;
    }

}
