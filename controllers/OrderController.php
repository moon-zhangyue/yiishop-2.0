<?php
/**
 * Created by PhpStorm.
 * User: htwy
 * Date: 2017/6/2
 * Time: 14:13
 */
namespace app\controllers;

use app\models\Address;
use app\models\Cart;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Pay;
use app\models\Product;
use app\models\User;
use app\controllers\CommonController;
use dzer\express\Express;
use Yii;
use yii\base\ErrorException;

class OrderController extends CommonController
{
    /*
     * 订单详情
     * */
    public function actionCheck()
    {
        $this->layout = 'layout1';
        //是否登录
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $orderid = Yii::$app->request->get('orderid');
        $status = Order::find()->where('orderid = :oid' ,[':oid'=>$orderid])->one()->status; //订单状态
        if($status != Order::CREATEORDER && $status != Order::CHECKORDER) return $this->redirect(['order/index']);

        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        $addresses = Address::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();
        $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $orderid])->asArray()->all();
        $data = [];
        foreach ( $details as $key => $value) {
            $products = Product::find()->where('productid = :pid' , [':pid' => $value['productid']])->asArray()->one(); //商品信息
            $value['title'] = $products['title'];
            $value['cover'] = $products['cover'];
            $data[] = $value;
        }
        $express = Yii::$app->params['express']; //快递信息
        $expressPrice = Yii::$app->params['expressPrice']; //运费
        return $this->render("check", ['express' => $express, 'expressPrice' => $expressPrice, 'addresses' => $addresses, 'products' => $data]);
    }

    /*
     * 订单首页
     * */
    public function actionIndex()
    {
        $this->layout = 'layout2';
        //是否登录
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        $orders = Order::getProducts($userid);
        return $this->render("index", ['orders' => $orders]);
    }

    /*
     * 添加订单
     * */
    public function actionAdd()
    {
        //是否登录
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }

        //开启事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();

                $ordermodel = new Order();
                $ordermodel -> scenario = 'add';

                $userinfo = User::find()->where('username = :name or useremail = :email ',[':name'=>Yii::$app->session['loginname'] , ':email'=>Yii::$app->session['loginname']])->asArray()->one();  //登录用户信息
//                p($userinfo);
                if(!$userinfo) throw new \Exception();

                $userid                 = $userinfo['userid'];
                $ordermodel->userid     = $userid;
                $ordermodel->status     = Order::CREATEORDER;
                $ordermodel->createtime = time();
                if(!$ordermodel->save())throw new \Exception();  //保存失败

                $orderid = $ordermodel->getPrimaryKey(); //获取订单id

                $model = new OrderDetail();
                foreach($post['OrderDetail'] as $key => $val){
                    $val['orderid']      = $orderid;
                    $val['createtime']   = time();
                    $data['OrderDetail'] = $val;
                    $result = $model->add($data);
                    if (!$result){
                        throw new \Exception();
                    }
//                    dd($post['OrderDetail']);
                    Cart::deleteAll('productid = :pid' , [':pid'=>$val['productid']]); //清除购物车此信息
                    Product::updateAllCounters(['num' => $val['productnum']],'productid = :pid',[':pid'=>$val['productid']]);//更改商品库存
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->redirect(['cart/index']);
        }
        return $this->redirect(['order/check' , 'orderid' => $orderid]);
    }

    /*
     * 订单提交
     * */
    public function actionConfirm()
    {
        //是否登录
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }

        try {
            if (!Yii::$app->request->isPost) {
                throw new \Exception();
            }
            $post = Yii::$app->request->post();
            $loginname = Yii::$app->session['loginname'];
            $usermodel = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->asArray()->one();
            if (empty($usermodel)) {
                throw new \Exception();
            }
            $userid = $usermodel['userid'];

            $model = Order::find()->where('orderid = :oid and userid = :uid', [':oid' => $post['orderid'], ':uid' => $userid])->one();
            if (empty($model)) {
                throw new \Exception();
            }
            $model->scenario = "update";
            $post['status'] = Order::CHECKORDER;
            $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $post['orderid']])->all();
            $amount = 0;
            foreach($details as $detail) {
                $amount += $detail->productnum*$detail->price; //商品总价
            }
            if ($amount <= 0) {
                throw new \Exception();
            }

            $express = Yii::$app->params['expressPrice'][$post['expressid']]; //快递总价
            if ($express < 0) {
                throw new \Exception();
            }

            $amount += $express;
            $post['amount'] = $amount; //合计总价
            $data['Order']  = $post;
//            p($post);
//            dd($data);
            if (empty($post['addressid'])) {
//                return $this->redirect(['order/check', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
                throw new \Exception();
            }
            if ($model->load($data) && $model->save()) {
                return $this->redirect(['order/pay', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
            }else{
                throw new \Exception();
            }

        } catch (ErrorException $e) {
//            Yii::warning("Division by zero".$e);
            return $this->redirect(['index/index']);
        }
    }

    /*
     * 支付
     * */
    public function actionPay()
    {
        //是否登录
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }

        try {
            $orderid = Yii::$app->request->get('orderid');  //订单id
            $paymethod = Yii::$app->request->get('paymethod');  //支付方式
            if (empty($orderid) || empty($paymethod)) {
                throw new \Exception();
            }
            if($paymethod == 'alipay'){
                return Pay::alipay($orderid); die;
            }
        }catch(\Exception $e){
//            p($e);
            die;
            return $this->redirect(['order/index']);
        }
    }

    /*
     * 查看物流
     * */
    public function actionGetexpress()
    {
        $expressno = Yii::$app->request->get('expressno');
        $res = Express::search($expressno);
        echo $res;
        exit;
    }

    /*
     *确认收货
     * */
    public function actionReceived()
    {
        $orderid = Yii::$app->request->get('orderid');
        $order = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one();
        if (!empty($order) && $order->status == Order::SENDED) {
            $order->status = Order::RECEIVED;
            $order->save();
        }
        return $this->redirect(['order/index']);
    }
}