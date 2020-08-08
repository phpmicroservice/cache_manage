<?php
# 测试脚手架
include_once "vendor/autoload.php";

$load = new \Composer\Autoload\ClassLoader();
$load->addPsr4('test\\', 'test');
$load->register();
# 进行di初始化
$mongo = new MongoDB\Client(
    'mongodb://zaoxingshi:zaoxingshi@172.0.9.220:27017/admin'
);
\MongoOdm\Di::init($mongo, 'log4php');