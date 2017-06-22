<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/4
 * Time: 10:12
 */
namespace app\modules\controllers;

use app\modules\models\Admin;
use yii\web\Controller;
use Yii;

class PublicController extends Controller
{
    public function actionLogin()
    {
        $this->layout = false;
        $model = new Admin;

        if(\Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model -> login($post)){
                $this->redirect(['default/index']);
                Yii::$app->end();
            }
//            p($post);
        }

        return $this->render('login',['model'=>$model]);
    }

    public function actionLogout()
    {
        Yii::$app->session->removeAll();
        if(!isset(Yii::$app->session['admin']['isLogin'])){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        $this->goback();
    }

    public function actionSeekpassword()
    {
        $this->layout = false;
        $model = new Admin;
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->seekPass($post)){
                Yii::$app->session->setFlash('info','电子邮件已经发送成功，请查收');
            }
        }
        return $this->render('seekpassword',['model'=>$model]);
    }
}