<?php

namespace test\Cache;

use CacheManage\AbstractCache;
use CacheManage\CacheInterface;
use test\Collection\User;
use CacheManage\Driver\Symfony;

/**
 * @method \test\Table\User get()
 */
class User1 extends AbstractCache
{

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
