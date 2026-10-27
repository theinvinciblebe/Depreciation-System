-- Migration: 002_create_depreciation_table.sql
-- Description: Creates the depreciation schedule and installment settlement table

CREATE TABLE IF NOT EXISTS `tbldepreciation` (
  `Depreid` BIGINT NOT NULL AUTO_INCREMENT,
  `cusid` BIGINT NULL DEFAULT NULL,
  `Principal` DOUBLE NULL DEFAULT NULL,
  `Interest_Month` DOUBLE NULL DEFAULT NULL,
  `Paid_Date` DATETIME NULL DEFAULT NULL,
  `Clear_Date` DATETIME NULL DEFAULT NULL,
  `clear_by_userid` BIGINT NULL DEFAULT NULL,
  PRIMARY KEY (`Depreid`) USING BTREE,
  KEY `idx_depre_cusid` (`cusid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
