<?php

/**
 * @method bool setTable(ManipleCore_Model_Table_Settings $table)
 * @method ManipleCore_Model_Table_Settings getTable()
 * @method ManipleCore_Model_Setting|null current()
 * @method ManipleCore_Model_Setting offsetGet(string $offset)
 * @method ManipleCore_Model_Setting getRow(int $position, $seek = false)
 */
class ManipleCore_Model_Rowset_Settings extends Zend_Db_Table_Rowset
{
    const className = __CLASS__;

    protected $_tableClass = ManipleCore_Model_Table_Settings::className;

    protected $_rowClass = ManipleCore_Model_Setting::className;
}
