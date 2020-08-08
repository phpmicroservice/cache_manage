<?php


class FirstTest extends \PHPUnit\Framework\TestCase
{

    public function testFirst()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));
    }

    public function testInster()
    {
        $data = [
            'username' => date('y-m-d h:i:s') . 'username',
            'password' => password_hash('123456', 1)
        ];
        $userdoc = new \test\Document\User();
        $userdoc->save($data);
        $count = (new \test\Collection\User())->count();
        $this->assertEquals(1, $count);
    }

    public function testAaa()
    {
        $config =[];

        $user = new \test\Cache\User1();
        $user->setDriver(new \CacheManage\Driver\Predis($config));
        $data = $user->get();
        dd($data);
    }
}