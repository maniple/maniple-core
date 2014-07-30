<?php

/**
 * PrefsManager interface.
 *
 * @package ManipleCore_Prefs
 */
interface ManipleCore_Prefs_PrefManagerInterface
{
    /**
     * Register user preference type and default value.
     *
     * @param  string $name
     * @param  ManipleCore_Prefs_PrefType $type
     * @return mixed
     */
    public function registerPref($name, ManipleCore_Prefs_PrefType $type);

    /**
     * Sanitize user preference based on registered types and default
     * values.
     *
     * @param  string $name
     * @param  mixed $value
     * @param  bool &$invalid OPTIONAL
     * @return mixed
     */
    public function sanitizePref($name, $value, &$invalid = null);

    /**
     * Set default preferences' values.
     *
     * @param  array $defaults
     * @return mixed
     */
    public function setDefaults(array $defaults);

    /**
     * Set default preference value.
     *
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function setDefault($name, $value);

    /**
     * Get default preference value.
     *
     * @param  string $name
     * @param  mixed $defaultValue OPTIONAL
     * @return mixed
     */
    public function getDefault($name, $defaultValue = null);

    /**
     * Get user preferences.
     *
     * @param  int|string $userId
     * @return ManipleCore_Prefs_UserPrefs
     */
    public function getUserPrefs($userId);

    /**
     * Get user preference.
     *
     * @param  int|string $userId
     * @param  string $name
     * @param  mixed $defaultValue OPTIONAL
     * @return mixed
     */
    public function getUserPref($userId, $name, $defaultValue = null);

    /**
     * Set user preference.
     *
     * @param  int|string $userId
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function setUserPref($userId, $name, $value);

    /**
     * Set user preferences.
     *
     * @param  int|string $userId
     * @param  array $prefs
     * @return mixed
     */
    public function setUserPrefs($userId, array $prefs);

    /**
     * Reset user preference.
     *
     * @param  int|string $userId
     * @param  string $name
     * @return mixed
     */
    public function resetUserPref($userId, $name);

    /**
     * Persist user preference changes to the adapter.
     *
     * @param  int|string $userId
     * @return mixed
     */
    public function saveUserPrefs($userId);

    /**
     * Loads user preferences from persistence adapter.
     *
     * @param  int|string $userId
     * @param  string $prefix OPTIONAL
     * @return string[]
     */
    public function loadUserPrefs($userId, $prefix = null);
}
