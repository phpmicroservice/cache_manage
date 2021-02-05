<?php

namespace test\Cache;

use CacheManage\Driver\Predis;

/**
 * Description of Time3
 * 三秒缓存
 * @author dongasai
 */
class Time3 extends AbstractCache
{
    // 单元测试用,正常使用无需引用
    use CacheTrait;

    public $ttl    = 3;
    protected $dirver = Predis::class;

    public function handle()
    {
        return time();
    }

}
