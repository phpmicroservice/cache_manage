<?php

namespace CacheManage;


class Helper
{

    /**
     * 更新标签
     * @param type $tag
     */
    static public function update_tag($tag)
    {
        $key = 'tage_' . $tag;
        $names = Cachef::get($key);
        if ($names) {
            foreach ($names as $name) {
                $ob = Cachef::get($name . '_ob');
                if ($ob instanceof AbstractCache) {
                    $ob->update();
                }
            }
        }


    }

}
