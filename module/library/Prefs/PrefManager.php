<?php

/**
 * Default PrefManager implementation.
 *
 * @package ManipleCore_Prefs
 */
class ManipleCore_Prefs_PrefManager
    implements ManipleCore_Prefs_PrefManagerInterface
{
    /**
     * @var ManipleCore_Prefs_AdapterInterface
     */
    protected $_adapter;

    /**
     * @var array
     */
    protected $_defaults;

    /**
     * @var array
     */
    protected $_prefs;

    /**
     * Constructor.
     *
     * @param  ManipleCore_Prefs_AdapterInterface $adapter
     * @return void
     */
    public function __construct(ManipleCore_Prefs_AdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Set default preferences' values.
     *
     * @param  array $defaults
     * @return ManipleCore_Prefs_PrefManager
     */
    public function setDefaults(array $defaults)
    {
        foreach ($defaults as $name => $value) {
            $this->setDefault($name, $value);
        }
        return $this;
    }

    /**
     * Set default preference value.
     *
     * @param  string $name
     * @param  mixed $value
     * @return ManipleCore_Prefs_PrefManager
     */
    public function setDefault($name, $value)
    {
        $name = (string) $name;
        if ($value === null) {
            unset($this->_defaults[$name]);
        } else {
            $this->_defaults[$name] = $value;
        }
        return $this;
    }

    /**
     * Get default preference value.
     *
     * @param  string $name
     * @param  mixed $defaultValue OPTIONAL
     * @return mixed
     */
    public function getDefault($name, $defaultValue = null)
    {
        $name = (string) $name;
        return isset($this->_defaults[$name]) ? $this->_defaults[$name] : $defaultValue;
    }

    /**
     * @param  int|string $userId
     * @param  bool|string $load
     * @return ManipleCore_Prefs_UserPrefs
     */
    public function getUserPrefs($userId, $load = true)
    {
        return new ManipleCore_Prefs_UserPrefs($this, $userId, $load);
    }

    /**
     * @param  int|string $userId
     * @param  string $name
     * @param  mixed $defaultValue OPTIONAL
     * @return mixed
     */
    public function getUserPref($userId, $name, $defaultValue = null)
    {
        $name = (string) $name;

        if (isset($this->_prefs[$userId]) &&
            array_key_exists($name, $this->_prefs[$userId])
        ) {
            $value = $this->_prefs[$userId][$name];
        } else {
            $this->_prefs[$userId][$name] = $this->_adapter->loadUserPref($userId, $name);
        }

        if ($value === null) {
            $value = $this->getDefault($name, $defaultValue);
        }

        return $value;
    }

    /**
     * @param  int|string $userId
     * @param  string $name
     * @param  mixed $value
     * @return ManipleCore_Prefs_PrefManager
     */
    public function setUserPref($userId, $name, $value)
    {
        $this->_prefs[$userId][(string) $name] = $value;
        return $this;
    }

    /**
     * @param  int|string $userId
     * @param  array $prefs
     * @return ManipleCore_Prefs_PrefManager
     */
    public function setUserPrefs($userId, array $prefs)
    {
        foreach ($prefs as $name => $value) {
            $this->setUserPref($userId, $name, $value);
        }
        return $this;
    }

    /**
     * @param  int|string $userId
     * @param  string $name
     * @return ManipleCore_Prefs_PrefManager
     */
    public function resetUserPref($userId, $name)
    {
        $this->_prefs[$userId][(string) $name] = null;
        return $this;
    }

    /**
     * @param  int|string $userId
     * @return ManipleCore_Prefs_PrefManager
     */
    public function saveUserPrefs($userId)
    {
        if (isset($this->_prefs[$userId])) {
            $this->_adapter->saveUserPrefs($userId, $this->_prefs[$userId]);
        }
        return $this;
    }

    /**
     * Loads user preferences from persistence adapter.
     *
     * @param  int|string $userId
     * @param  string $prefix OPTIONAL
     * @return string[]
     */
    public function loadUserPrefs($userId, $prefix = null)
    {
        $names = array();
        $prefs = $this->_adapter->loadUserPrefs($userId, $prefix);
        foreach ($prefs as $name => $value) {
            $name = (string) $name;
            $this->_prefs[$userId][$name] = $value;
            $names[] = $name;
        }
        return $names;
    }
}
