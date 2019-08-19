/**
 * MySQL schema definition for ManipleCore_Settings_Adapter_DbTable
 */
CREATE TABLE /* PREFIX */settings (

    setting_id      INT NOT NULL PRIMARY KEY AUTO_INCREMENT,

    name            VARCHAR(191) NOT NULL,

    value           TEXT NOT NULL,

    CONSTRAINT settings_name_idx UNIQUE (name)

) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
