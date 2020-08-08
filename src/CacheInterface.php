<?php

namespace CacheManage;

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
    public function get($param_arr, $time);

    /**
     * 获取键名
     * @return mixed
     */
    public function getKey();

    /**
     * 更新
     */
    public function update();


    /**
     * 获取自我标签
     * @return array
     */
    public function selfTags(): array;

    /**
     * 获取数据
     */
    public function handle();

}
