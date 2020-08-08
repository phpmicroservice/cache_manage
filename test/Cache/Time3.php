<?php

namespace test\Cache;

use CacheManage\Driver\Symfony;

/**
 * Description of Time3
 * 三秒缓存
 * @author dongasai
 */
class Time3 extends \CacheManage\AbstractCache
{
    // 单元测试用,正常使用无需引用
    use CacheTrait;

    protected $ttl    = 3;
    protected $dirver = Symfony::class;

    public function handle()
    {
        return time();
    }

}
