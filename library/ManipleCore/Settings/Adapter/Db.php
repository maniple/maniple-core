<?php

class ManipleCore_Settings_Adapter_Db implements ManipleCore_Settings_Adapter_Interface
{
    const className = __CLASS__;

    /**
     * @var ManipleCore_Model_Table_Settings
     */
    protected $_settingsTable;

    /**
     * @var ManipleCore_Model_Setting[]
     */
    protected $_settingRows;

    /**
     * @var array
     */
    protected $_settingValues = array();

    /**
     * @param Zefram_Db $db
     */
    public function __construct(Zefram_Db $db)
    {
        $this->_settingsTable = $db->getTable(ManipleCore_Model_Table_Settings::className);
    }

    public function get($name, $default = null)
    {
        $name = (string) $name;

        if (array_key_exists($name, $this->_settingValues)) {
            return $this->_settingValues[$name];
        }

        $settings = $this->_getSettingRows();

        if (isset($settings[$name])) {
            $row = $settings[$name];

            try {
                $decodedValue = Zefram_Json::decode($row->value);
            } catch (Zend_Json_Exception $e) {
                $decodedValue = $row->value;
            }

            return $this->_settingValues[$name] = $decodedValue;
        }

        return $default;
    }

    public function set($name, $value)
    {
        $name = (string) $name;

        $this->_settingValues[$name] = $value;
        $encodedValue = Zefram_Json::encode($value, array(
            'unescapedSlashes' => true,
            'unescapedUnicode' => true,
        ));

        $settings = $this->_getSettingRows();

        if (!isset($settings[$name])) {
            $setting = $settings[$name] = $this->_settingsTable->createRow();
            $setting->name = $name;
        } else {
            $setting = $settings[$name];
        }

        $setting->value = $encodedValue;
        $setting->saved_at = time();
        $setting->save();

        return $this;
    }

    public function remove($name)
    {
        $name = (string) $name;
        $result = $this->_settingsTable->delete(array('name = ?' => $name));

        unset($this->_settingRows[$name]);
        unset($this->_settingValues[$name]);

        return (bool) $result;
    }

    /**
     * Fetch all settings and store them as active records
     *
     * @return ManipleCore_Model_Setting[]
     */
    protected function &_getSettingRows()
    {
        if ($this->_settingRows === null) {
            $settings = array();
            foreach ($this->_settingsTable->fetchAll() as $row) {
                /** @var ManipleCore_Model_Setting $row */
                $settings[$row->name] = $row;
            }
            $this->_settingRows = $settings;
        }
        return $this->_settingRows;
    }
}
