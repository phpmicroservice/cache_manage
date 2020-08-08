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
    public static $Instance;

    public function __construct($config=null)
    {
        $this->predis = new Client($config);
    }

    /**
     * 单例模式获取
     * @return self
     */
    public static function getInstance($config =null)
    {
        if (!self::$Instance) {
            self::$Instance = new self($config);
        }
        return self::$Instance;
    }

    public function get($key, $default = null)
    {
        if(!$this->has($key)){
            return $default;
        }
        return unserialize($this->predis->get($key));
    }

    public function set(string $key, $value, int $ttl = 0): bool
    {
        $re =  $this->predis->set($key, serialize($value) ,'EX', $ttl);
        return $re->getPayload() == 'OK';    
    }

    public function remove($key)
    {
        return $this->predis->del($key);
    }

    public function has($key): bool
    {
        return $this->predis->exists($key);
    }

    public function clear()
    {
        return $this->predis->flushdb();
    }
}
