<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/18
 * Time: 21:59
 */
namespace app\controllers;

use app\controllers\CommonController;
use app\models\Pay;
use Yii;

class PayController extends CommonController
{
    public $enableCsrfValidation = false;   //关闭默认自带csrf验证

    public function actionNotify()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if(Pay::notify($post)){
                echo 'success';
                exit;
            }
            echo 'failed'; exit;
        }
    }

    public function actionReturn()
    {
        $this->layout = 'layout1';
        $status = Yii::$app->request->get('trade_status');
        if ($status == 'TRADE_SUCCESS') {
            $s = 'ok';
        } else {
            $s = 'no';
        }
        return $this->render("status", ['status' => $s]);
    }

}