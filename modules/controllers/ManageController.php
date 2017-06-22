<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/4
 * Time: 20:32
 */
namespace app\modules\controllers;

use app\modules\models\Admin;
use yii\data\Pagination;
use yii\web\Controller;
use Yii;
use app\modules\controllers\CommonController;

class ManageController extends CommonController
{
    //更改邮箱
    public function actionMailchangepass()
    {
        $this->layout = false;
        $time = \Yii::$app->request->get('timestamp');
        $adminuser = Yii::$app->request->get('adminuser');
        $token = Yii::$app->request->get('token');
        $model = new Admin;
        $myToken = $model->createToken($adminuser,$time);
        if($token != $myToken || time()-$time>300 ){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changePass($post)) {
                Yii::$app->session->setFlash('info', '密码修改成功');
            }
        }
        $model->adminuser = $adminuser;
        return $this->render('mailchangepass',['model'=>$model]);
    }

    //管理员列表
    public function actionManagers()
    {
        $this->layout = 'layout1';
        $model = Admin::find();
//        $managers = Admin::find()->all();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['manage'];
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);
        $managers = $model->offset($page->offset)->limit($page->limit)->all();
//        p($managers);
        return $this->render('managers',['managers'=>$managers,'page'=>$page]);
    }

    //添加管理员
    public function actionReg()
    {
        $this->layout = 'layout1';
        $model = new Admin;
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            // $post['Admin']['createtime'] = time();
                if($model->reg($post)){
                    Yii::$app->session->setFlash('info','添加成功');
                }else{
                    Yii::$app->session->setFlash('info','添加失败');
                }
        }
        $model->adminpass = '';
        $model->repass = '';
        return $this->render('reg',['model'=>$model]);
    }

    //删除管理员
    public function actionDel()
    {
        $adminid = (int)Yii::$app->request->get('adminid');
        if($adminid){
            $model = new Admin;
            if($model->deleteAll('adminid = :id',[':id'=>$adminid])){
                Yii::$app->session->setFlash('deladmin','删除成功');
            }else{
                // Yii::$app->session->setFlash('deladmin','删除失败');
            }
        }
        $this->redirect(['manage/managers']);

    }

    //管理员更改邮箱
    /**
     * @return Action
     */
    public function actionChangeemail()
    {
        $this->layout = 'layout1';
        $model = Admin::find()->where('adminuser = :user',[':user'=>Yii::$app->session['admin']['adminuser']])->one();
        $post = Yii::$app->request->post();
        if($post){
            if($model->changeemail($post)){
                Yii::$app->session->setFlash('changeemail','修改成功');
            }
        }
        $model->adminpass = '';
        return $this->render('changeemail',['model'=>$model]);
    }

    //管理员更改密码
    /**
     * @return Action
     */
    public function actionChangepass()
    {
        $this->layout = 'layout1';
        $model = Admin::find()->where('adminuser = :user',[':user'=>Yii::$app->session['admin']['adminuser']])->one();
        $post = Yii::$app->request->post();
        if($post){
            if($model->changepass($post)){
                Yii::$app->session->setFlash('changepass','修改成功');
            }
        }
        $model->adminpass = '';
        $model->repass = '';
        return $this->render('changepass',['model'=>$model]);
    }
}