<?php

class ManipleCore_Prefs_Adapter_DbTable
    implements ManipleCore_Prefs_AdapterInterface
{
    /**
     * @var ManipleCore_Prefs_Adapter_DbTable_UserPrefs
     */
    protected $_table;

    /**
     * Constructor.
     *
     * @param  Zefram_Db_Table_FactoryInterface $factory
     * @return void
     */
    public function __construct(Zefram_Db_Table_FactoryInterface $factory)
    {
        $this->_table = $factory->getTable('ManipleCore_Prefs_Adapter_DbTable_UserPrefs');
    }

    /**
     * Load user preference from database.
     *
     * @param  int|string $userId
     * @param  string $name
     * @return mixed
     */
    public function loadUserPref($userId, $name)
    {
        $name = (string) $name;
        $row = $this->_table->fetchRow(array(
            'user_id = ?'   => $userId,
            'pref_name = ?' => $name,
        ));
        if ($row) {
            return $row->pref_value;
        }
        return null;
    }

    /**
     * Load user preferences matching given prefix or, if none given,
     * all user preferences.
     *
     * @param  int|string $userId
     * @param  string $prefix OPTIONAL
     * @return array
     */
    public function loadUserPrefs($userId, $prefix = null)
    {
        $where = array('user_id = ?' => $userId);

        if ($prefix !== null) {
            $prefix = str_replace(array('%', '_', '^', '[', ']'), '', $prefix);
            $where['pref_name LIKE ?'] = $prefix . '%';
        }

        $prefs = array();
        foreach ($this->_table->fetchAll($where) as $row) {
            $prefs[$row->pref_name] = $row->pref_value;
        }
        return $prefs;
    }

    /**
     * Save single user preference.
     *
     * @param  int|string $userId
     * @param  string $name
     * @param  mixed $value
     * @return ManipleCore_Prefs_Adapter_DbTable
     */
    public function saveUserPref($userId, $name, $value)
    {
        $name = (string) $name;
        $this->_table->delete(array(
            'user_id = ?'   => $userId,
            'pref_name = ?' => $name,
        ));
        $this->_table->insert(array(
            'user_id'    => $userId,
            'pref_name'  => $name,
            'pref_value' => $value,
        ));
        return $this;
    }

    /**
     * Save multiple user preferences.
     *
     * @param  int|string $userId
     * @param  array $prefs
     * @return ManipleCore_Prefs_Adapter_DbTable
     */
    public function saveUserPrefs($userId, array $prefs)
    {
        $this->_table->delete(array(
            'user_id = ?'      => $userId,
            'pref_name IN (?)' => array_map('strval', array_keys($prefs)),
        ));
        foreach ($prefs as $name => $value) {
            $name = (string) $name;
            $this->_table->insert(array(
                'user_id'    => $userId,
                'pref_name'  => $name,
                'pref_value' => $value,
            ));
        }
        return $this;
    }
}
