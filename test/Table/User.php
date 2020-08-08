<?php

namespace test\Table;

/**
 * Description of User
 *
 * @author dongasai
 */
class User extends Table
{

    public $id; //字段
    public $fields = [
        'username',
        'password',
        'time',
        'teamId',
        'team'
    ];
    public $username;
    public $passsword;
    public $time;
    public $teamId;
    
    /**
     * @var \test\Table\Team
     */
    public $team;

    public function getUsername()
    {
        return "username_" . $this->id;
    }

    public function getPassword()
    {
        return md5($this->getUsername());
    }

    public function getTime()
    {
        return microtime(true);
    }

    public function getTeamId()
    {
        return $this->id % 20;
    }

    /**
     * 所属组
     * @return \test\Table\Team
     */
    public function getTeam()
    {
        return (new \test\Cache\Team([$this->getTeamId()]))->get();
    }

}
