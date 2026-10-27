-- Migration: 003_create_username_table.sql
-- Description: Creates the system users and administrators table

CREATE TABLE IF NOT EXISTS `tblusername` (
  `userid` BIGINT NOT NULL,
  `username` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `userpassword` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Photo` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`userid`) USING BTREE,
  UNIQUE KEY `idx_tblusername_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
