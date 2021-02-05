<?php

namespace CacheManage;

class Helper
{

    /**
     * 获取缓存key
     * @return type
     */
    public static function getKey($that)
    {   
        return md5(serialize($that));
    }

    /**
     * 转换为字符串Key 
     */
    public static function toKey()
    {
        return implode('_',func_get_args());
        return md5(serialize(func_get_args()));
    }

}
