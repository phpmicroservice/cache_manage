<?php

# 测试脚手架


function dump2()
{
    $trace = debug_backtrace();
    $stack = $trace[0];
    $file = isset($stack['file']) ? $stack['file'] : '';
    $line = isset($stack['line']) ? $stack['line'] : '';
//    $func = isset($stack['function']) ? $stack['function'] : '';
//         $args = isset($stack['args'])?$stack['args']:'';
    // $class = isset($stack['class'])?$stack['class']:'';
    echo " \n file :$file ; line :$line ";
    call_user_func_array('dump', func_get_args());
}


\CacheManage\Driver\Symfony::getInstance()->clear();

$config = [
    'host'   => 'redis',
    'port'   => 6379,
];
CacheManage\Driver\Predis::getInstance($config)->clear();

