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
    protected $fields = [
        "name",
        'time'
    ];

    public function getName()
    {
        return "teamName" . $this->id;
    }

    public function getTime()
    {
        return time();
    }

}
