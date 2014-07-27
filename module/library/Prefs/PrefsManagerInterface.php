<?php

/**
 * PrefsManager interface.
 *
 * @package ManipleCore_Prefs
 */
interface ManipleCore_Prefs_PrefsManagerInterface
{
    /**
     * Register user preference type and default value.
     *
     * @param  string $name
     * @param  ManipleCore_Prefs_PrefType $type
     */
    public function registerPref($name, ManipleCore_Prefs_PrefType $type);

    /**
     * Sanitize user preference based on registered types and default
     * values.
     *
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function sanitizePref($name, $value);

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
     * @return ManipleCore_Prefs_PrefsManagerInterface
     */
    public function setUserPref($userId, $name, $value);

    /**
     * Set user preferences.
     *
     * @param  int|string $userId
     * @param  array $prefs
     * @return ManipleCore_Prefs_PrefsManagerInterface
     */
    public function setUserPrefs($userId, array $prefs);

    /**
     * Reset user preference.
     *
     * @param  int|string $userId
     * @param  string $name
     * @return ManipleCore_Prefs_PrefsManagerInterface
     */
    public function resetUserPref($userId, $name);

    /**
     * Persist user preference changes to the adapter.
     *
     * @param  int|string $userId
     * @return ManipleCore_Prefs_PrefsManagerInterface
     */
    public function saveUserPrefs($userId);
}
