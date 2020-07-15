<?php

namespace App\Helper;

/**
 *
 * @author dongasai
 */
interface CacheInterface
{
    /**
     * 获取数据
     * @param type $time
     */
    public function get($param_arr,$time);
    
    public function getKey();
   


    /**
     * 更新
     */
    public function update();
    
    /**
     * 清空
     */
    public function flush();
    
    /**
     * 获取标签
     * @return array
     */
    public function tags():array;
    
    /**
     * 获取数据
     */
    public function handle();
    
}
