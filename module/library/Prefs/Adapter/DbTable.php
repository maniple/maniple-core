<?php

class ManipleCore_Prefs_Adapter_DbTable
    implements ManipleCore_Prefs_AdapterInterface
{
    /**
     * @var ManipleCore_Preferences_Adapter_DbTable_UserPrefs
     */
    protected $_table;

    public function __construct(Zefram_Db_Table_FactoryInterface $factory)
    {
        $this->_table = $factory->getTable('ManipleCore_Preferences_Adapter_DbTable_UserPrefs');
    }
}
