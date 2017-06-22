<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/22
 * Time: 10:45
 */

namespace app\modules\controllers;

use app\modules\controllers\CommonController;
use Yii;

class CommonController extends CommonController
{
    public function init()
    {
        if (Yii::$app->session['admin']['isLogin'] != 1) {
            return $this->redirect(['/admin/public/login']);
        }
    }
}
