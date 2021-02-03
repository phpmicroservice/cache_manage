<?php

namespace CacheManage;

/**
 * Description of AbstractCache
 *
 * @author dongasai
 */
abstract class AbstractCache implements CacheInterface
{

    protected $param_arr      = [];
    protected $ttl            = 60;
    protected $dirver         = null;

    /**
     * 
     * @var DriverInterface
     */
    protected $dirverInstance = null;

    public function __construct($param_arr = [], $ttl = null)
    {
        if (!$this->dirver) {
            $this->dirver = Driver\Predis::class;
        }

        if (!$this->dirverInstance) {
            $driver               = $this->dirver;
            $this->dirverInstance = $driver::getInstance();
        }

        if ($param_arr) {
            $this->param_arr = $param_arr;
        }
        if ($ttl !== null) {
            $this->ttl = $ttl;
        }
    }

    /**
     * 获取缓存key
     * @return type
     */
    public function getKey()
    {
        return md5(serialize($this));
    }

    /**
     * 获取值
     * @param type $param_arr
     * @param type $ttl
     * @return type
     */
    public function get($param_arr = [], $ttl = null)
    {
        if ($param_arr) {
            $this->param_arr = array_merge($this->param_arr, $param_arr);
        }
        if ($ttl !== null) {
            $this->ttl = $ttl;
        }
        $key = $this->getKey();
        if (!$this->dirverInstance->has($key)) {
            return $this->update();
        } else {
            return $this->dirverInstance->get($key);
        }
    }

    /**
     * 自动更新
     */
    public function update()
    {
        $key  = $this->getKey();
        $data = call_user_func_array([$this, 'handle'], $this->param_arr);
        $this->dirverInstance->set($key, $data, $this->ttl);
        $tags = $this->tags();
        if ($tags) {
            $this->tags_put($key, $tags, $this->ttl);
        }
        return $data;
    }

    /**
     * 标签储存
     * @param type $name
     * @param type $tags 标签列表
     */
    private function tags_put($name, $tags, $ttl)
    {
        
        $this->dirverInstance->set($this->getKey($name . '_ob'), $this, $ttl);
        foreach ($tags as $tag1) {
            $k       = $this->getKey('tage_' . $tag1);
            
            $names   = $this->dirverInstance->get($k, []);
            $names[] = $name;
            $names   = array_unique($names);
            $this->dirverInstance->set($k, $names, $ttl);
        }
    }

    public function __sleep()
    {
        return array('param_arr', 'ttl');
    }

}
