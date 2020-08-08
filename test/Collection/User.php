<?php

namespace test\Collection;

use MongoOdm\Collection;

class User extends Collection
{
    protected $_documentclass = \test\Document\User::class;

}