<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/18
 * Time: 15:30
 */
namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Address;

class AddressController extends CommonController
{
    /*
     * 添加联系人
     * */
    public function actionAdd()
    {
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $post['userid']  = $userid;
            $post['address'] = $post['address1'].$post['address2'];
            $data['Address'] = $post;
            $model = new Address;
            $res = $model->load($data);
            $result = $model->save();
//            p($res); p($result); die;
        }
        if(!$res || !$result) return false;
        if($_SERVER['HTTP_REFERER']){
            return $this->redirect($_SERVER['HTTP_REFERER']);
            //前一页信息http_referer由浏览器生成，并不是所有浏览器都会设置该值。http_referer可以伪造，并不可信。
        }
    }

    /*
     * 删除联系人
     * */
    public function actionDel()
    {
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        $addressid = Yii::$app->request->get('addressid');
        if (!Address::find()->where('userid = :uid and addressid = :aid', [':uid' => $userid, ':aid' => $addressid])->one()) {
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        Address::deleteAll('addressid = :aid', [':aid' => $addressid]);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}