<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class AccessFilter
{
    /**
     * 防刷中间件
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request_uri = $_SERVER['REQUEST_URI'];     //获取当前url
        $url_hash = substr(md5($request_uri),5,10);
        $max = env('API_ACCESS_MAX');                  //接口最大访问次数
        $expire = env('API_ACCESS_TIMEOUT');           // n秒后重试时间
        $key = 'count_url_'.$url_hash;
        $total = Redis::get($key);      // 获取访问次数

        if($total > $max){
            $response = [
                'errno' => 50010,
                'msg'   => "请求过于频繁，请 {$expire} 秒后再试"
            ];
            //设置key的过期时间
            Redis::expire($key,$expire);
            die( json_encode($response,JSON_UNESCAPED_UNICODE));
        }else{
            Redis::incr($key);
        }

        return $next($request);
    }
}
