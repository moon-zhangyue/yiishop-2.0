<?php
/**
 * Created by PhpStorm.
 * User: htwy
 * Date: 2017/6/2
 * Time: 13:34
 */

namespace app\controllers;

use app\models\Cart;
use app\models\Product;
use app\models\User;
//use yii\web\Controller;
use app\controllers\CommonController;
use Yii;

class CartController extends CommonController
{
    /*
     * 购物车页面
     * */
    public function actionIndex()
    {
        $this->layout = 'layout1';
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where('username = :name', [':name' => Yii::$app->session['loginname']])->one()->userid;
        $cart = Cart::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();
        $data = [];
        foreach ($cart as $k=>$v) {
            $product = Product::find()->where('productid = :pid', [':pid' => $v['productid']])->one();
            $data[$k]['cover']      = $product->cover;
            $data[$k]['title']      = $product->title;
            $data[$k]['productnum'] = $v['productnum'];
            $data[$k]['price']      = $v['price'];
            $data[$k]['productid']  = $v['productid'];
            $data[$k]['cartid']     = $v['cartid'];
        }
        return $this->render('index',['data' => $data]);
    }

    /*
     * 添加购物车
     * */
    public function actionAdd()
    {
        if(Yii::$app->session['isLogin'] !=1 ){
            return $this->redirect(['member/auth']);
        }

        $userid = User::find()->where('username = :name',[':name'=> Yii::$app->session['loginname']])->one()->userid;
        if(Yii::$app->request->isPost){
            $psot = Yii::$app->request->post();
            $num = Yii::$app->request->post()['productnum'];
            $data['Cart'] = $post;
            $data['Cart']['userid'] = $userid;
        }elseif(Yii::$app->request->isGet){
            $productid = Yii::$app->request->get('productid');
            $model = Product::find()->where('productid = :pid',[':pid'=>$productid])->one();
            $price = $model -> issale ? $model->saleprice : $model->price;
            $num = 1 ;
            $data['Cart'] = ['productid' => $productid, 'productnum' => $productnum , 'price' => $price , 'userid' => $userid];
        }
        if(!$model = Cart::find()->where('productid = :pid and userid = :uid',[':pid' => $data['Cart']['productid'], ':uid' => $data['Cart']['userid']])->one()){
            $model = new Cart;
            $data['Cart']['productnum'] = 1;
        }else{
            $data['Cart']['productnum'] = $model->productnum + $num;
        }
        $data['Cart']['createtime'] = time();
        $model -> load($data);
        $result = $model -> save();
//        dd($result);
        if($result) return $this->redirect(['cart/index']); //添加成功
        return $this->redirect(['index/index']);//添加失败

    }

    /*
     * 购物车加减操作
     * */
    public function actionMod()
    {
        $cartid = Yii::$app->request->get("cartid");
        $productnum = Yii::$app->request->get("productnum");
        Cart::updateAll(['productnum' => $productnum], 'cartid = :cid', [':cid' => $cartid]);
    }

    /*
     * 删除购物车
     * */
    public function actionDel()
    {
        $cartid = Yii::$app->request->get("cartid");
        Cart::deleteAll('cartid = :cid', [':cid' => $cartid]);
        return $this->redirect(['cart/index']);
    }
}