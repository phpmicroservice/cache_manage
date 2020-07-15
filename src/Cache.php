<?php

namespace App\Helper;

use \Illuminate\Support\Facades\Cache as Cachef;

class Cache
{

    /**
     * 缓存的回调 处理
     *
     * @param void $name2
     * @param int $time
     *            生命周期 秒
     * @param callable $callback
     * @param array $param_arr
     * @param bool $re
     * @return mixed|null
     */
    static public function cache_call($name2, int $time, callable $callback, $param_arr = [], $re = false)
    {
        if (is_array($name2)) {
            $name = md5(serialize($name2) . $time);
        } elseif (is_string($name2)) {
            $name = md5($name2 . $time);
        }
//         dd(\Illuminate\Support\Facades\Cache::has($name), $name);
        if ($re || !\Illuminate\Support\Facades\Cache::has($name)) {
            $data = call_user_func_array($callback, $param_arr);
            \Illuminate\Support\Facades\Cache::put($name, $data, $time);
            return $data;
        } else {
            return \Illuminate\Support\Facades\Cache::get($name);
        }
    }

    /**
     * 更新标签
     * @param type $tag
     */
    static public function update_tag($tag)
    {
        $key   = 'tage_' . $tag;
        $names = Cachef::get($key);
        if($names){
            foreach ($names as $name ){
                $ob  = Cachef::get($name.'_ob');
                if($ob instanceof AbstractCache){
                    $ob->update();
                }
            }
        }
        
     
    }

}
