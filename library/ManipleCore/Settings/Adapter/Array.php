<?php

/**
 * Array-based adapter for testing purposes
 */
class ManipleCore_Settings_Adapter_Array implements ManipleCore_Settings_Adapter_Interface
{
    /**
     * @var array
     */
    protected $_settings = array();

    public function get($name, $default = null)
    {
        $name = (string) $name;
        return isset($this->_settings[$name]) ? $this->_settings[$name] : $default;
    }

    public function set($name, $value)
    {
        $name = (string) $name;
        $this->_settings[$name] = $value;

        return $this;
    }

    public function remove($name)
    {
        $name = (string) $name;

        if (isset($this->_settings[$name])) {
            unset($this->_settings[$name]);
            return true;
        }

        return false;
    }
}
