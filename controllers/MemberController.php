<?php
/**
 * Created by PhpStorm.
 * User: htwy
 * Date: 2017/6/2
 * Time: 15:01
 */
namespace app\controllers;

use app\controllers\CommonController;
use app\models\User;
use Yii;

class MemberController extends CommonController
{
    /*
     * 用户登录
     * */
    public function actionAuth()
    {
        $this->layout = 'layout2';
        if (Yii::$app->request->isGet) {
            $url = Yii::$app->request->referrer;
            if (empty($url)) {
                $url = "/";
            }
            Yii::$app->session->setFlash('referrer', $url);
        }
        $model = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->login($post)) {
                $url = Yii::$app->session->getFlash('referrer');
                return $this->redirect($url);
            }
        }
        return $this->render("auth", ['model' => $model]);
    }

    /*
     * 用户注册
     * */
    public function actionReg()
    {
        $model = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->regByMail($post)) {
                Yii::$app->session->setFlash('info', '电子邮件发送成功');
            }
        }
        $this->layout = 'layout2';
        return $this->render('auth', ['model' => $model]);
    }

    /*
     * 用户退出
     * */
    public function actionLogout()
    {
        Yii::$app->session->remove('loginname');
        Yii::$app->session->remove('isLogin');
        if (!isset(Yii::$app->session['isLogin'])) {
            return $this->goBack(Yii::$app->request->referrer);
        }
    }

    /*
     * QQ登录
     * */
    public function actionQqlogin()
    {
        require_once("../vendor/qqlogin/qqConnectAPI.php");
        $oauth = new \OAuth();
        $accessToken = $auth->qq_callback();
        $openid = $auth->get_openid();
        $qc = new \QC($accessToken, $openid);
        $userinfo = $qc->get_user_info();
        $session = Yii::$app->session;
        $session['userinfo'] = $userinfo;
        $session['openid'] = $openid;
        if ($model = User::find()->where('openid = :openid', [':openid' => $openid])->one()) {
            $session['loginname'] = $model->username;
            $session['isLogin'] = 1;
            return $this->redirect(['index/index']);
        }
        return $this->redirect(['member/qqreg']);
    }

    /*
     * QQ登录处理
     * */
    public function actionQqcallback()
    {
        require_once("../vendor/qqlogin/qqConnectAPI.php");
        $qc = new \Qauth();
    }

    /*
     *完善信息
     * */
    public function actionQqreg()
    {
        $this->layout = "layout2";
        $model = new User;
//        P($_POST);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $session = Yii::$app->session;
            $session['openid'] = 'awjdawkdawkldkwakl123';//模拟数据
            $post['User']['openid'] = $session['openid'];
//            $post['User']['openid'] = Yii::$app->session['openid'];
            if ($model->reg($post, 'qqreg')) {
//                p($session);
                $session['loginname'] = $post['User']['username'];
                $session['isLogin'] = 1;
                return $this->redirect(['index/index']);
            }else{
                p('错误');
            }
        }
        return $this->render('qqreg', ['model' => $model]);
    }
}