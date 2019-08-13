<?php

class ManipleCore_Settings_Namespace
{
    /**
     * @var ManipleCore_Settings_SettingsManager
     */
    protected $_settingsManager;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @param ManipleCore_Settings_SettingsManager $settingsManager
     * @param string $name
     */
    public function __construct(ManipleCore_Settings_SettingsManager $settingsManager, $name)
    {
        $name = rtrim($name, '.');
        if (!strlen($name)) {
            throw new ManipleCore_Settings_Exception_InvalidArgumentException('Empty namespace name provided');
        }

        $this->_settingsManager = $settingsManager;
        $this->_name = $name;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->_settingsManager->get($this->_formatKey($key));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     * @throws ManipleCore_Settings_Exception_InvalidArgumentException If setting key is not registered
     */
    public function set($key, $value)
    {
        $this->_settingsManager->set($this->_formatKey($key), $value);
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function remove($key)
    {
        $this->_settingsManager->remove($this->_formatKey($key));
        return $this;
    }

    /**
     * @param string $key
     * @param string|array $type
     * @param array $options OPTIONAL
     * @return $this
     */
    public function register($key, $type = null, array $options = array())
    {
        $this->_settingsManager->register($this->_formatKey($key), $type, $options);
        return $this;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function _formatKey($key)
    {
        return sprintf('%s.%s', $this->_name, $key);
    }
}
