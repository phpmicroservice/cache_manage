<?php

namespace CacheManage;

/**
 *
 * @author dongasai
 */
interface DriverInterface
{

    public static function getInstance();

    public function has($key): bool;

    public function get($key, $default = null);

    public function set($key, $value, int $ttl = 0): bool;

    public function remove($key);
}
