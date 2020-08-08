<?php

namespace test\Collection;

use MongoOdm\Collection;

class Rule extends Collection
{
    protected $_documentclass = \test\Document\Rule::class;

}