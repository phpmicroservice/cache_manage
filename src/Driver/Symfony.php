<?php

namespace CacheManage\Driver;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Description of Symfony
 * Symfony的缓存驱动再封装
 * @author dongasai
 */
class Symfony extends \CacheManage\AbstractDriver
{

    static $instance;

    /**
     * 
     * @var FilesystemAdapter
     */
    private $cache;

    public function __construct($dir =null)
    {
        
        if(!$dir){
            $dir = $_SERVER['PWD'].DIRECTORY_SEPARATOR.'test/runtime';
        }
        $this->cache = new FilesystemAdapter('',0, $dir);
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

    public function has($key): bool
    {
        return $this->cache->hasItem($key);
    }

    public function get($key, $default = null)
    {
    
        $item =  $this->cache->getItem($key);
        if (!$item->isHit()) {
            return $default;
        }
        return $item->get();
    }

    public function set(string $key, $value, int $ttl = 0): bool
    {
        /**
         * @var \Symfony\Component\Cache\CacheItem $item
         */
        $item = $this->cache->getItem($key);
        $item->expiresAfter($ttl);
        $item->set($value);
        return $this->cache->save($item);
    }

    public function remove($key)
    {
        return $this->cache->deleteItem($key);
    }

    public function clear(){
        $this->cache->clear();
    }
}
