<?php

/**
 * @property string $name
 * @property string $value
 * @property int $saved_at
 * @method ManipleCore_Model_DbTable_Settings getTable()
 */
class ManipleCore_Model_Setting extends Zefram_Db_Table_Row
{
    const className = __CLASS__;

    protected $_tableClass = ManipleCore_Model_DbTable_Settings::className;
}
