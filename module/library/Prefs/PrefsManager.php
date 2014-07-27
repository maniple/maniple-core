<?php

/**
 * Default PrefsManager implementation.
 *
 * @package ManipleCore_Prefs
 */
class ManipleCore_Prefs_PrefsManager
    implements ManipleCore_Prefs_PrefsManagerInterface
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
     * @param  mixed $type
     * @param  mixed $defaultValue
     */
    public function registerPref($name, $type, $defaultValue)
    {
        // TODO
    }

    public function sanitizePref($name, $value)
    {
        // TODO
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
        if (isset($this->_prefs[$userId]) &&
            array_key_exists($name, $this->_prefs[$userId])
        ) {
            $value = $this->_prefs[$userId][$name];
        } else {
            $value = $this->_adapter->loadUserPref($userId, $name);

            if ($value !== null) {
                $value = $this->sanitizePref($name, $value);
            }

            $this->_prefs[$userId][$name] = $value;
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
     * @return ManipleCore_Prefs_PrefsManagerInterface
     */
    public function setUserPref($userId, $name, $value)
    {
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
     * @return ManipleCore_Prefs_PrefsManagerInterface
     */
    public function resetUserPref($userId, $name)
    {
        $this->_prefs[$userId][$name] = null;
        return $this;
    }

    /**
     * @param  int|string $userId
     * @return ManipleCore_Prefs_PrefsManagerInterface
     */
    public function saveUserPrefs($userId)
    {
        if (isset($this->_prefs[$userId])) {
            $this->_adapter->saveUserPrefs($userId, $this->_prefs[$userId]);
        }
        return $this;
    }
}
