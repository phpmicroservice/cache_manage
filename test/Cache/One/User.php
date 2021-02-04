<?php

namespace test\Cache\One;

use CacheManage\Driver\Symfony;

/**
 * @method \test\Table\User get()
 */
class User extends AbstractCache
{

    // 单元测试用,正常使用无需引用
    use \test\Cache\CacheTrait;

    protected $dirver = Symfony::class;

    public function selfTags(): array
    {
        return $this->selfTags;
    }

    public function handle()
    {
        $id                  = $this->param_arr[0] + 100;
        $this->selfTags[]    = "user_$id";
        $user                = new \test\Table\User(['id' => $id]);
        $teamId              = $user->getTeamId();
        $this->relatedTags[] = "team_$teamId";
        $team = (new \test\Cache\One\Team([$teamId]))->get();
        $user->team = $team;

        return $user;
    }

}
