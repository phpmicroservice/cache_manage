<?php

namespace CacheManage;

/**
 * Description of AbstractDriver
 * 驱动基类
 * @author zhenyou
 */
abstract class AbstractDriver implements DriverInterface
{

    protected static $Instance;
    
    /**
     * has 方法的别名
     * @param string $key
     * @return true
     */
    public function exists($key){
        return $this->has($key);
    }
}
