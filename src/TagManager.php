<?php

namespace CacheManage;

/**
 * Description of TagManager
 *
 * @author dongasai
 */
class TagManager
{
    
    public static function run(AbstractCache $that)
    {
        $key = Helper::toKey();
        $relatedTags = $that->relatedTags();
        if ($relatedTags) {
            self::tagsPut($key, $relatedTags, $this->ttl);
        }
        $selfTags = $that->selfTags();
        if ($selfTags) {
            foreach ($selfTags as $tag) {
                self::updateTag($tag);
            }
        }
    }

    /**
     * 更新标签
     * @param string $tag
     * @param string $key
     */
    public static function updateTag($tag)
    {
        $key = self::toKey('tage', $tag);
        $names = self::$dirverTagInstance->get($key);
        if ($names) {
            foreach ($names as $name) {
                $ob = self::$dirverTagInstance->get($name . '_ob');
                if ($ob instanceof AbstractCache) {
                    $ob->update($key);
                }
            }
        }
    }

    /**
     * 标签储存
     * @param string $name
     * @param array $tags
     * @param int $ttl
     */
    private static function tagsPut(string $name, array $tags, int $ttl)
    {
        self::$dirverTagInstance->set($name . '_ob', $this, $ttl);
        foreach ($tags as $tag1) {
            $k = $this->toKey('tage', $tag1);
            $names = self::$dirverTagInstance->get($k, []);
            $names[] = $name;
            $names = array_unique($names);
            self::$dirverTagInstance->set($k, $names, $ttl);
        }
    }

}
