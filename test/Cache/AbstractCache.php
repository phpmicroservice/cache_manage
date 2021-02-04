<?php

namespace test\Cache;

use CacheManage\Driver\Symfony;

/**
 * Description of AbstractCache
 * 覆盖原始基类，tag储存在 Symfony 驱动
 * @author dongasai
 */
abstract class AbstractCache extends \CacheManage\AbstractCache
{    
    protected function connect()
    {
        if (!$this->dirverInstance) {
            $driver               = $this->dirver;
            $this->dirverInstance = $driver::getInstance();
        }
        self::getTagDirverInstance();
    }
    
    
     /**
     * 标签储存驱动
     */
    protected static function getTagDirverInstance()
    {
        if (!self::$dirverTagInstance) {
            $driver = \CacheManage\Driver\Predis::class;
            self::$dirverTagInstance = $driver::getInstance();
        }
        return self::$dirverTagInstance;
    }

}
