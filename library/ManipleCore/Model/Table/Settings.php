<?php

/**
 * @method ManipleCore_Model_Setting findRow(mixed $id)
 * @method ManipleCore_Model_Setting createRow(array $data = array(), string $defaultSource = null)
 * @method ManipleCore_Model_Rowset_Settings find(mixed $key, mixed ...$keys)
 * @method ManipleCore_Model_Rowset_Settings fetchAll(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $count = null, int $offset = null)
 */
class ManipleCore_Model_Table_Settings extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManipleCore_Model_Setting::className;

    protected $_rowsetClass = ManipleCore_Model_Rowset_Settings::className;

    protected $_name = 'settings';

    protected $_referenceMap = array();
}
