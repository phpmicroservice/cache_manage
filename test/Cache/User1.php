<?php

namespace test\Cache;


use CacheManage\AbstractCache;
use CacheManage\CacheInterface;
use test\Collection\User;

class User1 extends AbstractCache
{


    public function tags(): array
    {
        return [
            'user1'
        ];
    }

    public function handle()
    {
        $data = (new User())->findFirst();
        return $data;


    }
}