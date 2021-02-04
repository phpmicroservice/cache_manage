<?php

namespace test\Cache;

use CacheManage\Driver\Predis;

/**
 * Description of Team
 * @method \test\Table\Team get()
 * @author dongasai
 */
class Team extends \CacheManage\AbstractCache
{
    // 单元测试用,正常使用无需引用
    use CacheTrait;

    protected $dirver = Predis::class;

    public function handle()
    {
        $id               = $this->param_arr[0];
        $team             = new \test\Table\Team(['id' => $id]);
        $this->selfTags[] = "team_$id";
        return $team;
    }

   
    
    
}
