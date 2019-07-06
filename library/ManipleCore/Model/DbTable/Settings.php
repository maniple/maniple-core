<?php

/**
 * @method ManipleCore_Model_Setting findRow(mixed $id)
 * @method ManipleCore_Model_Setting createRow(array $data = array(), string $defaultSource = null)
 */
class ManipleCore_Model_DbTable_Settings extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManipleCore_Model_Setting::className;

    protected $_name = 'settings';

    protected $_referenceMap = array();
}
