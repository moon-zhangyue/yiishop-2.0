<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/11
 * Time: 14:45
 */

namespace app\modules\controllers;
use app\models\Category;
use Yii;
use app\modules\controllers\CommonController;


class CategoryController extends CommonController
{
    /*
     * 商品分类列表
     * */
    public function actionList()
    {
        $this->layout = 'layout1';
        $model = new Category;
        $cates = $model->getTreeList();
        return $this->render('cates',['cates'=>$cates]);
    }

    /*
     * 添加分类
     * */
    public function actionAdd()
    {
        $this->layout = 'layout1';

//        $list = ['添加顶级分类'];
        $model = new Category();
        $list = $model->getOptions();
//        $cates= $model->getData();
//        $tree = $model->getTree($cates);
//        $tree = $model->setPrefix($tree);
//        dd($tree);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->add($post)){
                Yii::$app->session->setFlash('info','添加成功');
            }
        }
        return $this->render('add',['list'=>$list,'model'=>$model]);
    }

    /*
     * 修改分类
     * */
    public function actionMod()
    {
        $this->layout = 'layout1';
        $cateid = Yii::$app->request->get('cateid');
        $model = Category::find()->where('cateid = :id',[':id'=>$cateid])->one();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->save()){
                Yii::$app->session->setFlash('info','修改成功');
            }
        }
        $list = $model->getOptions();
        return $this->render('add',['model'=>$model,'list'=>$list]);
    }

    /*
     * 删除分类
     * */
    public function actionDel()
    {
        try{
            $cateid = Yii::$app->request->get('cateid');
            if(empty($cateid)){
                throw new \Exception('参数错误');
            }

            $data = Category::find()->where('parentid = :pid ',[':pid'=>$cateid])->one();
            if($data){
                throw new \Exception('该分类下有子类,不允许删除');
            }
            if(!Category::deleteAll('cateid = :id',[':id'=>$cateid])){
                throw new \Exception('删除失败');
            }
            Yii::$app->session->setFlash('info','删除成功');
        }catch(\Exception $e){
            Yii::$app->session->setFlash('info',$e->getMessage());
        }
        return $this->redirect(['category/list']);
    }






}