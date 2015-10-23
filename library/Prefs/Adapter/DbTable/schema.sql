--
-- Table structure for table `user_prefs`
--

CREATE TABLE %PREFIX%user_prefs (
    
    user_id         INTEGER NOT NULL,

    pref_name       VARCHAR(255) NOT NULL,

    pref_value      VARCHAR(255) NOT NULL,

    PRIMARY KEY (pref_name, pref_value)

) /*! ENGINE = InnoDB DEFAULT CHARSET=utf8 */;

ALTER TABLE %PREFIX%user_prefs ADD CONSTRAINT %PREFIX%user_prefs_user_id_fkey
    FOREIGN KEY (user_id) REFERENCES %PREFIX%users (user_id);

