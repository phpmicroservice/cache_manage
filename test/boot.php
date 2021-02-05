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
    echo "  file :$file ; line :$line \n";
    call_user_func_array('var_dump', func_get_args());
}

/**
 * 进行毫秒延迟
 * @param int $ms
 */
function msleep($ms = 1)
{
    usleep($ms * 1000);
}

$config = [
    'host'   => '192.168.1.132',
    'port'   => 6379,
];
\CacheManage\Driver\Predis::getInstance($config);