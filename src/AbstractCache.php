<?php

namespace CacheManage;

/**
 * Description of AbstractCache
 *
 * @author dongasai
 */
abstract class AbstractCache implements CacheInterface
{

    protected $param_arr = [];
    protected $ttl = 60;
    protected $dirver = null;
    protected $selfTags = [];
    protected $relatedTags = [];

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
            $driver = $this->dirver;
            $this->dirverInstance = $driver::getInstance();
        }
    }

    /**
     * 标签储存驱动
     */
    protected static function getTagDirverInstance()
    {
        if (!self::$dirverTagInstance) {
            $driver = \CacheManage\Driver\Predis::class;
            self::$dirverTagInstance = $driver::getInstance();
        }
        return self::$dirverTagInstance;
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
        $key = Helper::getKey($this);
        if (!$this->dirverInstance->has($key)) {

            return $this->update();
        } else {
            return $this->dirverInstance->get($key);
        }
    }

    /**
     * 更新数据
     * @return 
     */
    public function update()
    {
        $key = Helper::getKey($this);
        try {
            $data = call_user_func_array([$this, 'handle'], $this->param_arr);
            $this->dirverInstance->set($key, $data, $this->ttl);
        } catch (NotFindException $e) {
            $this->dirverInstance->remove($key);
            $data = null;
        }        
        return $data;
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
     * 默认自我标签
     * @return array
     */
    public function selfTags(): array
    {
        $this->selfTags[] = __CLASS__;
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
