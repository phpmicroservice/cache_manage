<?php

namespace CacheManage;

/**
 * Description of AbstractCache
 *
 * @author dongasai
 */
abstract class AbstractCache implements CacheInterface
{

    protected $param_arr   = [];
    protected $ttl         = 60;
    protected $dirver      = null;
    protected $selfTags    = [];
    protected $relatedTags = [];

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
        $this->connect();
        if ($param_arr) {
            $this->param_arr = $param_arr;
        }
        if ($ttl !== null) {
            $this->ttl = $ttl;
        }
    }

    private function connect()
    {
        if (!$this->dirverInstance) {
            $driver               = $this->dirver;
            $this->dirverInstance = $driver::getInstance();
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
            $this->param_arr = $param_arr;
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
     * 更新数据
     * @param null|string $upTag
     * @return 
     */
    public function update($upTag = null)
    {
        $key = $this->getKey();
        try {
            $data = call_user_func_array([$this, 'handle'], $this->param_arr);
            $this->dirverInstance->set($key, $data, $this->ttl);
        } catch (NotFindException $e) {
            $this->dirverInstance->remove($key);
            $data = null;
        }

        $relatedTags = $this->relatedTags();
        if ($relatedTags) {
            $this->tags_put($key, $relatedTags, $this->ttl);
        }
        $selfTags = $this->selfTags();
        if ($selfTags) {
            $this->tags_update($selfTags);
        }
        return $data;
    }

    /**
     * 转换为字符串Key 
     */
    private function toKey()
    {
        return md5(serialize(func_get_args()));
    }

    /**
     * 标签储存
     * @param type $name
     * @param type $tags 标签列表
     */
    private function tags_put($name, $tags, $ttl)
    {
        $this->dirverInstance->set($name . '_ob', $this, $ttl);
        foreach ($tags as $tag1) {
            $k       = $this->toKey('tage', $tag1);
            $names   = $this->dirverInstance->get($k, []);
            $names[] = $name;
            $names   = array_unique($names);
            $this->dirverInstance->set($k, $names, $ttl);
        }
    }

    /**
     * 
     * @param string $key
     * @param array $tags
     */
    private function tags_update( $tags)
    {
        foreach ($tags as $tag) {
            $key = $this->toKey('tage', $tag);
            $this->update_tag2( $key);
        }
    }

    /**
     * 更新标签
     * @param string $tag
     * @param string $key
     */
    public function update_tag2($key)
    {
        $names = $this->dirverInstance->get($key);
        if ($names) {
            foreach ($names as $name) {
                $ob = $this->dirverInstance->get($name . '_ob');
                if ($ob instanceof AbstractCache) {
                    $ob->update($key);
                }
            }
        }
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
