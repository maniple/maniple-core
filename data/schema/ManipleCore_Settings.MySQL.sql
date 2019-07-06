/**
 * MySQL schema definition for ManipleCore_Settings_Adapter_DbTable
 */
CREATE TABLE /* PREFIX */settings (

    name            VARCHAR(128) NOT NULL PRIMARY KEY,

    value           TEXT NOT NULL,

    saved_at        INTEGER NOT NULL

) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
