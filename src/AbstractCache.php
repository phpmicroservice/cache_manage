<?php

namespace CacheManage;

/**
 * Description of AbstractCache
 *
 * @author dongasai
 */
abstract class AbstractCache implements CacheInterface
{

    public $param_arr   = [];
    public $ttl         = 600;
    protected $dirver      = null;
    protected $selfTags    = [];
    protected $relatedTags = [];
    public $dirverTagInstance;
    /**
     * @var DriverInterface 
     */
    protected $dirverInstance = null;

    public function __construct($param_arr = [], $ttl = null)
    {
        if (!$this->dirver) {
            $this->dirver = Driver\Predis::class;
        }
        $this->connect();
        if ($param_arr) {
            $this->param_arr = $param_arr;
        }
        if ($ttl !== null) {
            $this->ttl = $ttl;
        }
    }

    protected function connect()
    {
        if (!$this->dirverInstance) {
            $driver               = $this->dirver;
            $this->dirverInstance = $driver::getInstance();
        }
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
            $this->param_arr = $param_arr;
        }
        if ($ttl !== null) {
            $this->ttl = $ttl;
        }
        $this->relatedTags = array();
        $this->selfTags = array();
        $key = Helper::getKey($this);
        if (!$this->dirverInstance->has($key)) {
            return $this->update();
        } else {
            return $this->dirverInstance->get($key);
        }
    }

    /**
     * 更新数据,可覆盖次方法
     * @return 
     */
    public function update()
    {
        $key = Helper::getKey($this);
        try {
            $data = call_user_func_array([$this, 'handle'], $this->param_arr);
            $this->dirverInstance->set($key, $data, $this->ttl);
            $this->__update();
        } catch (NotFindException $e) {
            $this->dirverInstance->remove($key);
            $data = null;
        }
        return $data;
    }

    public function __update()
    {
        TagManager::getInstance()->run($this);
    }

    /**
     * 序列化所需的属性
     * @return array
     */
    public function __sleep()
    {
        return array('param_arr', 'ttl');
    }

    public function __wakeup()
    {
        $this->connect();
    }

    /**
     * 自我标签
     * @return array
     */
    public function selfTags(): array
    {
        return $this->selfTags;
    }

    /**
     * 获取关联标签
     * @return array
     */
    public function relatedTags(): array
    {
        return $this->relatedTags;
    }

}
