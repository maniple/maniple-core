<?php

/**
 * DbTable based preference persistence adapter
 *
 * @package ManipleCore_Prefs
 * @uses    Zefram_Db_Table
 * @uses    Zend_Serializer
 * @author  xemlock
 * @version 2014-07-30
 */
class ManipleCore_Prefs_Adapter_DbTable
    implements ManipleCore_Prefs_AdapterInterface
{
    /**
     * @var ManipleCore_Prefs_Adapter_DbTable_UserPrefs
     */
    protected $_table;

    /**
     * @var Zend_Serializer_Adapter_Interface
     */
    protected $_serializer;

    /**
     * Constructor.
     *
     * @param  Zefram_Db_Table_FactoryInterface $factory
     * @param  Zend_Serializer_Adapter_Interface|string $serializer
     * @return void
     */
    public function __construct(Zefram_Db_Table_FactoryInterface $factory, $serializer = null)
    {
        $this->_table = $factory->getTable('ManipleCore_Prefs_Adapter_DbTable_UserPrefs');

        if ($serializer !== null) {
            if (!$serializer instanceof Zend_Serializer_Adapter_Interface) {
                $serializer = Zend_Serializer::factory($serializer);
            }
            $this->_serializer = $serializer;
        }
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
            return $this->_unserialize($row->pref_value);
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
            $prefs[$row->pref_name] = $this->_unserialize($row->pref_value);
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
            'pref_value' => $this->_serialize($value),
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
                'pref_value' => $this->_serialize($value),
            ));
        }
        return $this;
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    protected function _unserialize($value)
    {
        if ($this->_serializer) {
            try {
                $value = $this->_serializer->unserialize($value);
            } catch (Exception $e) {
                $value = null;
            }
        }
        return $value;
    }

    /**
     * @param  mixed $value
     * @return string
     */
    protected function _serialize($value)
    {
        if ($this->_serializer) {
            $value = $this->_serializer->serialize($value);
        } elseif (is_bool($value)) {
            $value = (int) $value;
        } elseif (is_float($value)) {
            // do not cast to float, as during conversion from float to string
            // E notation is used for big numbers
            $value = sprintf('%F', $value);
        }
        return (string) $value;
    }
}
