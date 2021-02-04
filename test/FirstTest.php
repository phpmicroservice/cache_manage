<?php

class FirstTest extends \PHPUnit\Framework\TestCase
{

    static $time;

    public function testFirst()
    {
        $user1                = new test\Cache\User1([1]);
        $re1                  = $user1->get();
        self::$time[0]        = $re1->time;
        self::$time['userteam_1'] = $re1->team->time;
        $this->assertLessThanOrEqual(time(), self::$time[0]);
        sleep(1);
        $re2                  = $user1->get();
        // 读取缓存时间不变
        $this->assertEquals(self::$time[0], $re2->time);
        $user1->update();
        $re3                  = $user1->get();
        // 新数据，最新时间
        $this->assertGreaterThan(self::$time[0], $re3->time);
        // 组数据为旧数据
        $this->assertEquals(self::$time['userteam_1'], $re3->team->time);
    }

    public function test2()
    {
        sleep(2);
        $team  = new test\Cache\Team([1]);
//        更新 组 数据
        $team->update();
        $user1 = new test\Cache\User1([1]);
        $reUser= $user1->get();
        // 
        $this->assertGreaterThan(self::$time['userteam_1'], $reUser->team->time);
    }

}
