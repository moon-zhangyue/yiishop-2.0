<?php
/**
 * Created by PhpStorm.
 * User: htwy
 * Date: 2017/6/2
 * Time: 11:35
 */
namespace app\controllers;

//use yii\console\Controller;
use yii\web\Controller;

class ProductController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'layout2';
        return $this->renderPartial('index');
    }

    public function actionDetail()
    {
        $this->layout = 'layout2';
        return $this->renderPartial('detail');
    }
}