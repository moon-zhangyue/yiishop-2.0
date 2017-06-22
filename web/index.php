<?php

// comment out the following two lines when deployed to production


defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');  //开发模式

require(__DIR__ . '/../helper/function.php');  //自定义全局打印方法

require(__DIR__ . '/../vendor/autoload.php');  // 注册 Composer 自动加载器
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');  // 包含 Yii 类文件

$config = require(__DIR__ . '/../config/web.php');  // 加载应用配置

(new yii\web\Application($config))->run();  // 创建、配置、运行一个应用
