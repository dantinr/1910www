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


    /**
     * 发送数据
     */
    public function  sign1()
    {
        $key = 'sdlfkjsdlfkjsfsdf';
        $data = 'hello world';
        $sign = sha1($data.$key);     //生成签名

        echo "要发送的数据: ".$data;echo '</br>';
        echo "发送前生成的签名: ". $sign;echo '<hr>';

        //将数据和签名发送到对端
        $b_url = 'http://www.1910.com/secret?data='.$data.'&sign='.$sign;

        echo $b_url;
    }


    /**
     * 接收数据
     */
    public function secret()
    {
        $key = 'sdlfkjsdlfkjsfsdf';
        echo '<pre>';print_r($_GET);echo '</pre>';
        //收到数据 验证签名
        $data = $_GET['data'];      //接收到的数据
        $sign = $_GET['sign'];      // 接收到的签名

        $local_sign = sha1($data.$key);      //验签算法 与 发送端的生成签名算法保持一致 md5(data+key)
        echo '本地计算的签名: '.$local_sign;echo '</br>';

        if($sign == $local_sign)
        {
            echo "验签通过";
        }else{
            echo "验签失败";
        }
    }

}
