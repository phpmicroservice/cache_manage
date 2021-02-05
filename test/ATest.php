<?php

/**
 * 标签储存在 Symfony 驱动
 * @example vendor/bin/phpunit  --stop-on-failure test/ATest.php
 */
class ATest extends \PHPUnit\Framework\TestCase
{

    static $time;

    public function testFirst()
    {
        \CacheManage\TagManager::getInstance()->updateTags(['team_1']);
    }    

}
