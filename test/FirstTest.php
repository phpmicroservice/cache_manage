<?php

/**
<<<<<<< HEAD
 * 标签储存在默认驱动 （Predis 驱动）
=======
 * 
>>>>>>> 444d94d... init
 */
class FirstTest extends \PHPUnit\Framework\TestCase
{

    static $time;

    public function testFirst()
    {
        $user1 = new \test\Cache\User([1]);

        $this->assertInstanceOf(\CacheManage\Driver\Symfony::class, $user1->dirverInstance());
        $this->assertInstanceOf(\CacheManage\Driver\Predis::class, $user1->dirverTagInstance());

        $re1                      = $user1->get();
        self::$time[0]            = $re1->time;
        self::$time['userteam_1'] = $re1->team->time;
        $this->assertLessThanOrEqual(microtime(true), self::$time[0]);
        sleep(1);
        $re2                      = $user1->get();
        // 读取缓存时间不变
        $this->assertEquals(self::$time[0], $re2->time);
        $user1->update();
        $re3                      = $user1->get();
        // 新数据，最新时间
        $this->assertGreaterThan(self::$time[0], $re3->time);
        // 组数据为旧数据
        $this->assertEquals(self::$time['userteam_1'], $re3->team->time);
        // 同一个组，时间一样
        $this->assertEquals(self::$time['userteam_1'], $user1->get([21])->team->time);
//        、、 不同的组，无缓存，时间最新
        $user2                    = $user1->get([2]);
        $this->assertGreaterThan(self::$time['userteam_1'], $user2->team->time);
    }

    public function test2()
    {
        sleep(1);
        $team    = new test\Cache\Team([1]);
        $this->assertInstanceOf(\CacheManage\Driver\Predis::class, $team->dirverInstance());
        $this->assertInstanceOf(\CacheManage\Driver\Predis::class, $team->dirverTagInstance());
//        更新 组 数据
        $team->update();
        $user1   = new test\Cache\User([1]);
        $reUser  = $user1->get();
        $newTime = $reUser->team->time;
        // 会关联更新，组成员数据，时间最新
        $this->assertGreaterThan(self::$time['userteam_1'], $reUser->team->time);
        $this->assertGreaterThan(self::$time['userteam_1'], $user1->get([21])->team->time);
        // 非组成员，不更新，时间滞后
        $this->assertLessThan($newTime, $user1->get([2])->team->time);
        sleep(1);
        // 全新的组
        $user4   = $user1->get([4]);
        $this->assertGreaterThan($newTime, $user4->team->time);
    }

    public function test3()
    {
        $time3 = new test\Cache\Time3();
        $old   = $time3->get();
        $this->assertEquals($old, $time3->get());
        sleep(1);
        $this->assertEquals($old, $time3->get());
        sleep(3);
        $this->assertGreaterThan($old, $time3->get());
    }

}
