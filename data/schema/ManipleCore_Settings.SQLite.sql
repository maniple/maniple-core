/**
 * SQLite schema definition for ManipleCore_Settings_Adapter_DbTable
 */
CREATE TABLE /* PREFIX */settings (

    name            VARCHAR(128) NOT NULL PRIMARY KEY,

    value           TEXT NOT NULL,

    saved_at        INTEGER NOT NULL

);
