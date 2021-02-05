<?php

namespace CacheManage;

use CacheManage\Driver\Predis;
use CacheManage\Driver\Symfony;

/**
 * Description of TagManager
 *
 * @author dongasai
 */
class TagManager
{

    public $dirver = Predis::class;
    /**
     * 
     * @var DriverInterface
     */
    public $dirverInstance;
    static $Instance;

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

    public function run(AbstractCache $AbstractCache)
    {
        $AbstractCache->dirverTagInstance = $this->dirverInstance;
        $key         = Helper::getKey($AbstractCache);
        $relatedTags = $AbstractCache->relatedTags();
        if ($relatedTags) {
            $this->tagsPut($key, $relatedTags, $AbstractCache);
        }
        $selfTags = $AbstractCache->selfTags();
        if ($selfTags) {
            foreach ($selfTags as $tag) {
                $this->updateTag($tag);
            }
            // 自我标签储存
            $this->tagsPut($key, $selfTags, $AbstractCache,'stag');
        }
    }

    /**
     * 更新标签
     * @param string $tag
     * @param string $key
     */
    public function updateTag($tag, $prefix = 'rtag')
    {
        $key   = Helper::toKey($prefix, $tag);
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
    
    public function updateTags($tags)
    {
        foreach ($tags as $tag){
            $this->updateTag($tag);
            $this->updateTag($tag,'stag');
        }
    }

    /**
     * 标签储存
     * @param string $name
     * @param array $tags
     * @param int $ttl
     */
    private function tagsPut(string $name, array $tags, AbstractCache $AbstractCache,$prefix = 'rtag')
    {
        
        $this->dirverInstance->set($name . '_ob', $AbstractCache,31536000);
        foreach ($tags as $tag1) {
            $k   = Helper::toKey($prefix, $tag1);
            
            $names   = $this->dirverInstance->get($k, []);
            if(!in_array($tag1, $names)){
                $names[] = $name;
            }
            $this->dirverInstance->set($k, $names,31536000);
        }
    }
    

}
