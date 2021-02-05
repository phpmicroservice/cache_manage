<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace test\Cache\One;

use CacheManage\Driver\Symfony;

/**
 * Description of TagManager
 *
 * @author dongasai
 */
class TagManager extends \CacheManage\TagManager
{

    public $dirver = Symfony::class;
    public static $Instance;

    public function __construct()
    {
        $driver               = $this->dirver;
        $this->dirverInstance = $driver::getInstance();
    }

    /**
     * 单例模式获取
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$Instance) {
            self::$Instance = new self();
        }
        return self::$Instance;
    }

}
