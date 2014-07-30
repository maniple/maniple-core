<?php

/**
 * This is a proxy class for calling PrefsManager methods using a fixed
 * user ID.
 *
 * @package ManipleCore_Prefs
 */
class ManipleCore_Prefs_UserPrefs
{
    /**
     * @var ManipleCore_Prefs_PrefManagerInterface
     */
    protected $_prefManager;

    /**
     * @var array
     */
    protected $_prefNames;

    /**
     * @var int|string
     */
    protected $_userId;

    /**
     * Constructor.
     *
     * @param  ManipleCore_Prefs_PrefManagerInterface $prefsManager
     * @param  int|string $userId
     * @param  bool|string $load
     */
    public function __construct(ManipleCore_Prefs_PrefManagerInterface $prefManager, $userId, $load = true)
    {
        if (!is_int($userId) && !is_string($userId)) {
            throw new InvalidArgumentException('User ID must either be an integer or a string');
        }

        $this->_prefManager = $prefManager;
        $this->_userId = $userId;

        if ($load) {
            $prefix = is_string($load) ? $load : null;
            $names = $this->_prefManager->loadUserPrefs($userId, $prefix);
            $this->_prefNames = array_flip($names);
        }
    }

    /**
     * Get user preference.
     *
     * @param  string $name
     * @param  mixed $defaultValue
     * @return mixed
     */
    public function get($name, $defaultValue = null)
    {
        $value = $this->_prefManager->getUserPref($this->_userId, $name, $defaultValue);
        $this->_prefNames[(string) $name] = true;
        return $value;
    }

    /**
     * @param  string $name
     * @param  bool $defaultValue OPTIONAL
     * @return bool|null
     */
    public function getBool($name, $defaultValue = null)
    {
        $value = $this->get($name, $defaultValue);
        if ($value !== null) {
            $value = (bool) $value;
        }
        return $value;
    }

    /**
     * @param  string $name
     * @param  int $defaultValue OPTIONAL
     * @return int|null
     */
    public function getInt($name, $defaultValue = null)
    {
        $value = $this->get($name, $defaultValue);
        if ($value !== null) {
            $value = (int) $value;
        }
        return $value;
    }

    /**
     * @param  string $name
     * @param  float $defaultValue OPTIONAL
     * @return float|null
     */
    public function getFloat($name, $defaultValue = null)
    {
        $value = $this->get($name, $defaultValue);
        if ($value !== null) {
            $value = (float) $value;
        }
        return $value;
    }

    /**
     * @param  string $name
     * @param  string $defaultValue OPTIONAL
     * @return string|null
     */
    public function getString($name, $defaultValue = null)
    {
        $value = $this->get($name, $defaultValue);
        if ($value !== null) {
            $value = (string) $value;
        }
        return $value;
    }

    /**
     * Set user preference.
     *
     * @param  string $name
     * @param  mixed $value
     * @return ManipleCore_Prefs_UserPrefs
     */
    public function set($name, $value)
    {
        $this->_prefManager->setUserPref($this->_userId, $name, $value);
        $this->_prefNames[(string) $name] = true;
        return $this;
    }

    /**
     * Reset user preference.
     *
     * @param  string $name
     * @return ManipleCore_Prefs_UserPrefs
     */
    public function reset($name)
    {
        $this->_prefManager->resetUserPref($this->_userId, $name);
        unset($this->_prefNames[(string) $name]);
        return $this;
    }

    /**
     * Persist user preferences.
     *
     * @return ManipleCore_Prefs_UserPrefs
     */
    public function save()
    {
        $this->_prefManager->saveUserPrefs($this->_userId);
        return $this;
    }

    /**
     * Return names of loaded preferences.
     *
     * @return string[]
     */
    public function getNames()
    {
        return array_keys((array) $this->_prefNames);
    }
}
