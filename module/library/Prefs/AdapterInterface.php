<?php

interface ManipleCore_Prefs_AdapterInterface
{
    /**
     * @param  int|string $userId
     * @param  string $name
     * @return mixed
     */
    public function loadUserPref($userId, $name);

    /**
     * Load user preferences matching given prefix or, if none given,
     * all user preferences.
     *
     * @param  int|string $userId
     * @param  string $prefix OPTIONAL
     * @return array
     */
    public function loadUserPrefs($userId, $prefix = null);

    /**
     * @param  int|string $userId
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function saveUserPref($userId, $name, $value);

    /**
     * @param  int|string $userId
     * @param  string $name
     * @param  array $prefs
     * @return mixed
     */
    public function saveUserPrefs($userId, array $prefs);
}
