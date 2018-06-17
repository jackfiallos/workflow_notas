<?php

$config = dirname(__FILE__).'/protected/config/';
$frameworkPath = '/../../yii-1.1.14.f0fee9/framework/';
$yiifile = 'yii.php';

// error_reporting(E_ALL); 
// ini_set('display_errors', '1');
date_default_timezone_set('America/Mexico_City');

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

$yiifile = ((YII_DEBUG === true) ? 'yii.php' : 'yiilite.php');
$yii = dirname(__FILE__).$frameworkPath.$yiifile;

$configFile = ((YII_DEBUG === true) ? 'dev.php' : 'prod.php');

require_once($yii);

Yii::createWebApplication($config . $configFile)->run();