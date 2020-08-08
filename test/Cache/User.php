<?php

namespace test\Cache;

use CacheManage\AbstractCache;
use CacheManage\Driver\Symfony;

/**
 * @method \test\Table\User get()
 */
class User extends AbstractCache
{
    // 单元测试用,正常使用无需引用
    use CacheTrait;

    protected $dirver = Symfony::class;

    public function selfTags(): array
    {
        return $this->selfTags;
    }

    public function handle()
    {
        $id                  = $this->param_arr[0];
        $this->selfTags[]    = "user_$id";
        $user                = new \test\Table\User(['id' => $id]);
        $teamId              = $user->getTeamId();
        $this->relatedTags[] = "team_$teamId";
        
        return $user;
    }

}
