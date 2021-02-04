<?php

namespace test\Table;

/**
 * Description of Team
 *
 * @author dongasai
 */
class Team extends Table
{

    public $id;
    public $time;
    public $name;
    
    public $fields = [
        "name",
        'time'
    ];

    public function getName()
    {
        return "teamName" . $this->id;
    }

    public function getTime()
    {
        return microtime(true);
    }

}
