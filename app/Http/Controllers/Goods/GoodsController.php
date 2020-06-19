<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\GoodsModel;

class GoodsController extends Controller
{

    /**
     * 商品详情页
     */
    public function detail()
    {
        $goods_id = $_GET['id'];        //接收url的get参数 id
        echo 'goods_id: '. $goods_id;echo '</br>';

        //查询商品详情
        //$info = GoodsModel::where(['goods_id'=>$goods_id])->first()->toArray();
        $info = GoodsModel::find($goods_id);            // find 查找主键
        var_dump($info);
        echo '<pre>';print_r($info);echo '</pre>';

    }
}
