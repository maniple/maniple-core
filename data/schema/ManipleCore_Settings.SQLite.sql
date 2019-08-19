/**
 * SQLite schema definition for ManipleCore_Settings_Adapter_DbTable
 */
CREATE TABLE /* PREFIX */settings (

    setting_id      INTEGER NOT NULL PRIMARY KEY,

    name            VARCHAR(191) NOT NULL,

    value           TEXT NOT NULL,

    CONSTRAINT settings_name_idx UNIQUE (name)

);
