<?php

namespace test\Cache;

/**
 * Description of CacheTrait
 *
 * @author zhenyou
 */
trait CacheTrait
{

    /**
     * 获取标签储存驱动，此处用于单元测试，正常使用无需实现
     */
    public function dirverTagInstance()
    {
        return self::$dirverTagInstance;
    }

    /**
     * 获取储存驱动，此处用于单元测试，正常使用无需实现
     */
    public function dirverInstance()
    {
        return $this->dirverInstance;
    }

}
