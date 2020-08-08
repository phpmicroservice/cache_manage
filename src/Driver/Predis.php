<?php

namespace CacheManage\Driver;
use Predis\Client;

/**
 * Class Predis
 * @package CacheManage
 * @author dongasai
 * @property-read  Client $predis
 */
class Predis implements \CacheManage\DriverInterface
{
    private $predis;

    public function __construct($config)
    {
        $this->predis = new Client($config);
    }

    public function get($key, $default = null)
    {
        return $this->predis->get($key);
    }

    public function set($key, $value, $ttl = 0):bool
    {
        return $this->predis->set($key,$value,$ttl);
    }

    public function remove($key)
    {
        return $this->predis->del($key);
    }

    public function has($key):bool
    {
        return $this->predis->exists($key);
    }
}