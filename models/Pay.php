<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/18
 * Time: 20:24
 */

namespace app\models;

use app\models\Order;
use app\models\OrderDetail;
use app\models\Product;

class Pay{
    /*
     * 支付
     * */
    public static function alipay($orderid)
    {
        $amount = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one()->amount;
        if(!empty($amount)){
//            echo getcwd() . "<br/>";
//            echo dirname(__FILE__);
//            echo $_SERVER['DOCUMENT_ROOT'];

            $alipay = new \AlipayPay();
//            $alipay = new \vendor\AliPay\AlipayPay();
            $giftname = "yii商城";
            $data = OrderDetail::find()->where('orderid = :oid', [':oid' => $orderid])->all();
            $body = "";
            foreach($data as $pro) {
                $body .= Product::find()->where('productid = :pid', [':pid' => $pro['productid']])->one()->title . " - ";
            }
            $body .= "等商品";
            $showUrl = "http://www.yiishop.com";
            $html = $alipay->requestPay($orderid, $giftname, $amount, $body, $showUrl);
            echo $html;
        }
    }

    /*
     * 回调
     * */
    public static function notify($data)
    {
        $alipay = new \AlipayPay();
        $verify_result = $alipay->verifyNotify();  //支付验证结果
        if($verify_result){
            $out_trade_no = $data['extra_common_param']; //订单号
            $trade_no     = $data['trade_no'];  //支付号
            $trad_status  = $data['trade_status'];   //支付状态
            $status = Order::PAYFAILED;
            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS'){
                $status = Order::PAYSUCCESS;
                $order_info = Order::find()->where('orderid = :oid', [':oid' => $out_trade_no])->one();
                if (!$order_info) {
                    return false;
                }
                if ($order_info->status == Order::CHECKORDER) {
                    Order::updateAll(['status' => $status, 'tradeno' => $trade_no, 'tradetext' => json_encode($data)], 'orderid = :oid', [':oid' => $order_info->orderid]);
                } else {
                    return false;
                }
            }
            return true;
        }else{
            return false;
        }
    }

}