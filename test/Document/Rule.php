<?php

namespace test\Document;

use MongoOdm\Document\Document;

class Rule extends Document
{

    protected $_collection_class = \test\Collection\Rule::class;

}