<?php

namespace test\Document;

use MongoOdm\Document\Document;

class User extends Document
{

    protected $_collection_class = \test\Collection\User::class;

}