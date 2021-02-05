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
     public function __update()
    {
        TagManager::getInstance()->run($this);
    }

}
