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
    protected $_prefs;

    /**
     * @var array
     */
    protected $_registeredPrefs;

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
     * @param  string $name
     * @param  ManipleCore_Prefs_PrefType $type
     * @return ManipleCore_Prefs_PrefManager
     */
    public function registerPref($name, ManipleCore_Prefs_PrefType $type)
    {
        $name = (string) $name;
        $this->_registeredPrefs[$name] = $type;
        return $this;
    }

    /**
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function sanitizePref($name, $value)
    {
        // only non-NULL values are sanitized, as NULL is a perfectly
        // valid value which means that given preference is not set
        if ($value !== null) {
            $name = (string) $name;
            if (isset($this->_registeredPrefs[$name])) {
                return $this->_registeredPrefs[$name]->getValue($value);
            }
        }
        return $value;
    }

    /**
     * @param  int|string $userId
     * @return ManipleCore_Prefs_UserPrefs
     */
    public function getUserPrefs($userId)
    {
        return new ManipleCore_Prefs_UserPrefs($this, $userId);
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
            $value = $this->_adapter->loadUserPref($userId, $name);
            $this->_prefs[$userId][$name] = $this->sanitizePref($name, $value);
        }

        if ($value === null) {
            $value = $defaultValue;
        }

        return $value;
    }

    /**
     * @param  int|string $userId
     * @param  string $name
     * @param  mixed $value
     * @return ManipleCore_Prefs_PrefManagerInterface
     */
    public function setUserPref($userId, $name, $value)
    {
        $name = (string) $name;
        $this->_prefs[$userId][$name] = $value;
        return $this;
    }

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
     * @return ManipleCore_Prefs_PrefManagerInterface
     */
    public function resetUserPref($userId, $name)
    {
        $name = (string) $name;
        $this->_prefs[$userId][$name] = null;
        return $this;
    }

    /**
     * @param  int|string $userId
     * @return ManipleCore_Prefs_PrefManagerInterface
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
     * @return ManipleCore_Prefs_PrefManagerInterface
     */
    public function loadUserPrefs($userId, $prefix = null)
    {
        $prefs = $this->_adapter->loadUserPrefs($userId, $prefix);
        foreach ($prefs as $name => $value) {
            $name = (string) $name;
            $this->_prefs[$userId][$name] = $this->sanitizePref($name, $value);
        }
        return $this;
    }
}
