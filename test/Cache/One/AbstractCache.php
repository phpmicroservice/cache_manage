<?php

namespace test\Cache\One;

use CacheManage\Driver\Symfony;

/**
 * Description of AbstractCache
 * 覆盖原始基类，tag储存在 Symfony 驱动
 * @author dongasai
 */
abstract class AbstractCache extends \CacheManage\AbstractCache
{
    /**
     * 覆盖原始基类，tag储存在 Symfony 驱动
     */
    protected static $dirverTag = Symfony::class;

}
