<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/12
 * Time: 20:42
 */

namespace app\modules\controllers;

use app\models\Product;
use app\models\Category;
use crazyfd\qiniu\Qiniu;
use yii\web\Controller;
use Yii;
use yii\data\Pagination;
use app\modules\controllers\CommonController;


class ProductController extends CommonController
{
    /*
     * 商品列表
     * */
    public function actionList()
    {
        $this->layout = 'layout1';
        $model = Product::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['product'];
        $pager = new Pagination(['totalCount' => $count , 'pageSize' => $pageSize]);
        $products = $model->offset($pager->offset)->limit($pager->limit)->all();
//        p($products);
        return $this->render('products',['pager'=>$pager,'products'=>$products]);
    }


    /*
     * 添加商品
     * */
    public function actionAdd()
    {
        $this->layout = 'layout1';
        $model = new Product;
        $cate = new Category;
        $list = $cate->getOptions();
        unset($list[0]);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $pics = $this->upload();
            if (!$pics) {
                $model->addError('cover', '封面不能为空');
            } else {
                $post['Product']['cover'] = $pics['cover'];
                $post['Product']['pics'] = $pics['pics'];
            }
            if ($pics && $model->add($post)) {
                Yii::$app->session->setFlash('info', '添加成功');
            } else {
                Yii::$app->session->setFlash('info', '添加失败');
            }
        }

        return $this->render('add',['opts' => $list, 'model' => $model]);
    }

    /*
     *上传图片
     * */
    public function upload()
    {
        if($_FILES['Product']['error']['cover'] > 0) return false;
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $key = uniqid();
        $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
        $cover = $qiniu->getLink($key);
        $pics = [];
        foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
            if ($_FILES['Product']['error']['pics'][$k] > 0) {
                continue;  //说明有错
            }
            $key = uniqid();
            $qiniu->uploadFile($file, $key);
            $pics[$key] = $qiniu->getLink($key);
        }
        return ['cover' => $cover, 'pics' => json_encode($pics)];
    }

    /*
     * 编辑商品
     * */
    public function actionMod()
    {
        $this->layout = "layout1";
        $cate = new Category;
        $list = $cate->getOptions();
        unset($list[0]);

        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :id', [':id' => $productid])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
            $post['Product']['cover'] = $model->cover;
            if ($_FILES['Product']['error']['cover'] == 0) {
                $key = uniqid();
                $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
                $post['Product']['cover'] = $qiniu->getLink($key);
                $qiniu->delete(basename($model->cover));
            }
            $pics = [];
            foreach($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
                if ($_FILES['Product']['error']['pics'][$k] > 0) {
                    continue;

                }
                $key = uniqid();
                $qiniu->uploadfile($file, $key);
                $pics[$key] = $qiniu->getlink($key);

            }
            $post['Product']['pics'] = json_encode(array_merge((array)json_decode($model->pics, true), $pics));
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '修改成功');

            }

        }
        return $this->render('add', ['model' => $model, 'opts' => $list]);

    }

    /*
     * 删除图片
     * */
    public function actionRemovepic()
    {
        $key = Yii::$app->request->get("key");  //接收key
        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);  //删除key对应的值
        $pics = json_decode($model->pics, true);
        unset($pics[$key]);  //删除图片
        Product::updateAll(['pics' => json_encode($pics)], 'productid = :pid', [':pid' => $productid]);  //跟新数据
        return $this->redirect(['product/mod', 'productid' => $productid]);
    }

    /*
     * 删除商品
     * */
    public function actionDel()
    {
        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $key = basename($model->cover); //取得cover路径中的文件名部分
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);  //删除封面图片
        $pics = json_decode($model->pics, true);
        foreach($pics as $key=>$file) {
            $qiniu->delete($key);  //删除对应图片
        }
        Product::deleteAll('productid = :pid', [':pid' => $productid]); //更新数据库
        return $this->redirect(['product/list']);
    }

    /*
     * 商品上架
     * */
    public function actionOn()
    {
        $productid = Yii::$app->request->get("productid");
        Product::updateAll(['ison' => '1'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }


    /*
     * 商品下架
     * */
    public function actionOff()
    {
        $productid = Yii::$app->request->get("productid");
        Product::updateAll(['ison' => '0'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }
}