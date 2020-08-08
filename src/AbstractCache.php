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

    public function __construct($param_arr = [], $ttl = null)
    {

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
        if (!\Illuminate\Support\Facades\Cache::has($key)) {
            return $this->update();
        } else {
            return \Illuminate\Support\Facades\Cache::get($key);
        }
    }


    /**
     * 自动更新
     */
    public function update()
    {
        $key = $this->getKey();
        $data = call_user_func_array([$this, 'handle'], $this->param_arr);
        \Illuminate\Support\Facades\Cache::put($key, $data, $this->ttl);
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
        Cache::put($name . '_ob', $this, $ttl);
        foreach ($tags as $tag1) {
            if (is_string($tag1)) {
                $k = 'tage_' . $tag1;
                $names = Cache::get($k, []);
                $names[] = $name;
                $names = array_unique($names);
                Cache::put($k, $names, $ttl);
            } else {
                throw new Exception("非字符串标签");
            }
        }
    }

    public function __sleep()
    {
        return array('param_arr', 'ttl');
    }

}
