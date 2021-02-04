<?php

namespace test\Cache;

use CacheManage\Driver\Symfony;


/**
 * Description of Team
 * @method \test\Table\Team get()
 * @author dongasai
 */
class Team extends \CacheManage\AbstractCache
{
    protected $dirver = Symfony::class;
    
    public function handle()
    {
        $id = $this->param_arr[0];
        $team = new \test\Table\Team(['id'=>$id]);
        $this->selfTags[] = "team_$id";
        return $team;
    }

}
