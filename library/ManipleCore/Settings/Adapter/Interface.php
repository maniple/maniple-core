<?php

interface ManipleCore_Settings_Adapter_Interface
{
    /**
     * Retrieve setting by name
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * Set setting value
     *
     * @param string $name
     * @param mixed $value
     * @return $this Provides fluent interface
     */
    public function set($name, $value);

    /**
     * Remove setting
     *
     * @param string $name
     * @return bool TRUE if setting is successfully deleted, FALSE if setting does not exist
     */
    public function remove($name);
}
