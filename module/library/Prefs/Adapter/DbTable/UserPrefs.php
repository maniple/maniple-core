<?php

class ManipleCore_Preferences_Adapter_DbTable_UserPreferences
    extends Zefram_Db_Table
{
    protected $_name = 'user_prefs';

    protected $_primary = array('user_id', 'pref_name');

    protected $_sequence = false;
}
