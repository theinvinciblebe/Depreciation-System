-- Migration: 001_create_customer_table.sql
-- Description: Creates the customer profile table with Unicode and Khmer language support

CREATE TABLE IF NOT EXISTS `tblcustomer` (
  `cusid` BIGINT NOT NULL,
  `cusname` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custel` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IDCard` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cusAddress` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Photo` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProductName` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProductPrice` DOUBLE NULL DEFAULT NULL,
  `Interest` DOUBLE NULL DEFAULT NULL,
  `Duration` INT NULL DEFAULT NULL,
  `Create_Date` DATETIME NULL DEFAULT NULL,
  `Userid` BIGINT NULL DEFAULT NULL,
  PRIMARY KEY (`cusid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
