<?php

class ManipleCore_Model_DbTable_Users extends Zefram_Db_Table
{
    /**
     * @var string
     */
    protected $_name = 'users';

    /**
     * @var string
     */
    protected $_primary = 'user_id';

    /**
     * @var bool
     */
    protected $_sequence = true;
}
