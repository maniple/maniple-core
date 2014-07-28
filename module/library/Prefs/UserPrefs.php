<?php

/**
 * This is a proxy class for calling PrefsManager methods using a fixed
 * user ID.
 *
 * @package ManipleCore_Prefs
 */
class ManipleCore_Prefs_UserPrefs implements ArrayAccess
{
    /**
     * @var ManipleCore_Prefs_PrefManagerInterface
     */
    protected $_prefManager;

    /**
     * @var int|string
     */
    protected $_userId;

    /**
     * Constructor.
     *
     * @param  ManipleCore_Prefs_PrefManagerInterface $prefsManager
     * @param  int|string $userId
     */
    public function __construct(ManipleCore_Prefs_PrefManagerInterface $prefManager, $userId)
    {
        if (!is_int($userId) && !is_string($userId)) {
            throw new InvalidArgumentException('User ID must either be an integer or a string');
        }

        $this->_prefManager = $prefManager;
        $this->_userId = $userId;
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
        return $this->_prefManager->getUserPref($this->_userId, $name, $defaultValue);
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
     * Required by ArrayAccess interface, proxy to {@link get()}.
     *
     * @param  string $name
     * @return mixed
     */
    public function offsetGet($name)
    {
        return $this->get($name);
    }

    /**
     * Required by ArrayAccess interface, proxy to {@link set()}.
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($name, $value)
    {
        $this->set($name);
    }

    /**
     * Get preference and check if it is not null. Required by ArrayAccess
     * interface.
     *
     * @param  string $name
     * @return bool
     */
    public function offsetExists($name)
    {
        return $this->get($name) !== null;
    }

    /**
     * Required by ArrayAccess interface, proxy to {@link reset()}.
     *
     * @param  string $name
     * @return void
     */
    public function offsetUnset($name)
    {
        $this->reset($name);
    }

    /**
     * Proxy to {@link get()}.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Proxy to {@link set()}.
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Get preference and check if it is not null.
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->get($name) !== null;
    }

    /**
     * Proxy to {@link reset()}.
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        $this->reset($name);
    }
}
