<?php

/**
 * @method ManipleCore_Model_Setting findRow(mixed $id)
 * @method ManipleCore_Model_Setting createRow(array $data = array(), string $defaultSource = null)
 * @method Zend_Db_Table_Rowset_Abstract|ManipleCore_Model_Setting[] find(mixed $key, mixed ...$keys)
 * @method Zend_Db_Table_Rowset_Abstract|ManipleCore_Model_Setting[] fetchAll(mixed $where = null, string|array $order = null, int $count = null, int $offset = null)
 */
class ManipleCore_Model_DbTable_Settings extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManipleCore_Model_Setting::className;

    protected $_name = 'settings';

    protected $_referenceMap = array();
}
