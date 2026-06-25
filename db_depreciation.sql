/*
 Navicat Premium Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 100428
 Source Host           : localhost:3306
 Source Schema         : db_depreciation

 Target Server Type    : MySQL
 Target Server Version : 100428
 File Encoding         : 65001

 Date: 31/12/2023 10:45:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tblcustomer
-- ----------------------------
DROP TABLE IF EXISTS `tblcustomer`;
CREATE TABLE `tblcustomer`  (
  `cusid` bigint NOT NULL,
  `cusname` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `custel` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `IDCard` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `cusAddress` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `Photo` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ProductName` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ProductPrice` double NULL DEFAULT NULL,
  `Interest` double NULL DEFAULT NULL,
  `Duration` int NULL DEFAULT NULL,
  `Create_Date` datetime NULL DEFAULT NULL,
  `Userid` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`cusid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tblcustomer
-- ----------------------------
INSERT INTO `tblcustomer` VALUES (1, 'Admin', '08321555', '002341', 'KPC', 'iphone15.jpg', '15 Pro max', 1500, 1.3, 8, '2023-05-15 14:23:46', 23);
INSERT INTO `tblcustomer` VALUES (2, 'Sonita', '092333124', '002414', 'PP', 'Mac.jpg', 'MAC Book', 2300, 0.8, 12, '2023-08-06 14:33:36', 43);
INSERT INTO `tblcustomer` VALUES (3, 'G-Devit', '012222222', '002 413', 'PP', 'Ipad.jpg', 'Ipad', 1290, NULL, NULL, NULL, NULL);
INSERT INTO `tblcustomer` VALUES (20231212051222, 'Twin_Gabby', '085 808 587', '5555', 'PP', 'photo_2023-05-18_12-21-17.jpg', 'Huawei', 2500, 1.5, 10, '2023-12-12 05:12:22', 6);
INSERT INTO `tblcustomer` VALUES (20231213031250, 'Be Chkout', '5656', '123', 'KPC', '32.jpeg', 'Vivo', 2500, 0.5, 12, '2023-12-13 03:12:50', 10);
INSERT INTO `tblcustomer` VALUES (20231226111212, 'Regret', '577583', '123', 'VN', '24.jpeg', 'Huawei', 333, 0.5, 10, '2023-12-26 11:12:12', 1);

-- ----------------------------
-- Table structure for tbldepreciation
-- ----------------------------
DROP TABLE IF EXISTS `tbldepreciation`;
CREATE TABLE `tbldepreciation`  (
  `Depreid` bigint NOT NULL AUTO_INCREMENT,
  `cusid` bigint NULL DEFAULT NULL,
  `Principal` double NULL DEFAULT NULL,
  `Interest_Month` double NULL DEFAULT NULL,
  `Paid_Date` datetime NULL DEFAULT NULL,
  `Clear_Date` datetime NULL DEFAULT NULL,
  `clear_by_userid` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`Depreid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20231212051276 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbldepreciation
-- ----------------------------
INSERT INTO `tbldepreciation` VALUES (1, 1, 200, 40, '2023-07-17 10:35:23', '0000-00-00 00:00:00', 1);
INSERT INTO `tbldepreciation` VALUES (2, 1, 200, 40, '2023-08-17 20:27:26', '0000-00-00 00:00:00', 1);
INSERT INTO `tbldepreciation` VALUES (3, 1, 200, 40, '2023-09-17 10:41:03', '2023-12-19 10:26:00', -2);
INSERT INTO `tbldepreciation` VALUES (4, 2, 240, 40, '2023-07-21 10:35:23', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (5, 2, 240, 40, '2023-08-21 10:42:30', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (6, 2, 240, 40, '2023-09-21 10:42:48', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (7, 3, 258, 38.7, '2023-11-21 10:50:15', '2023-11-22 09:21:54', 3);
INSERT INTO `tbldepreciation` VALUES (8, 3, 258, 38.7, '2023-12-21 10:50:15', '2023-12-22 09:22:01', 3);
INSERT INTO `tbldepreciation` VALUES (9, 3, 258, 38.7, '2024-01-21 10:50:15', '2024-01-22 09:22:11', 3);
INSERT INTO `tbldepreciation` VALUES (10, 3, 258, 38.7, '2024-02-21 10:50:15', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (11, 3, 258, 38.7, '2024-03-21 10:50:15', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (12, 1, 200, 40, '2023-10-17 12:52:40', NULL, 1);
INSERT INTO `tbldepreciation` VALUES (20231212051223, 20231212051222, 250, 37.5, '2024-01-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051224, 20231212051222, 250, 37.5, '2024-02-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051225, 20231212051222, 250, 37.5, '2024-03-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051226, 20231212051222, 250, 37.5, '2024-04-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051227, 20231212051222, 250, 37.5, '2024-05-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051228, 20231212051222, 250, 37.5, '2024-06-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051229, 20231212051222, 250, 37.5, '2024-07-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051230, 20231212051222, 250, 37.5, '2024-08-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051231, 20231212051222, 250, 37.5, '2024-09-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051232, 20231212051222, 250, 37.5, '2024-10-12 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051233, 20231213031250, 208.33333333333, 12.5, '2024-01-13 00:00:00', '2023-12-13 03:22:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051234, 20231213031250, 208.33333333333, 12.5, '2024-02-13 00:00:00', '2023-12-13 03:22:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051235, 20231213031250, 208.33333333333, 12.5, '2024-03-13 00:00:00', '2023-12-13 03:22:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051236, 20231213031250, 208.33333333333, 12.5, '2024-04-13 00:00:00', '2023-12-13 03:22:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051237, 20231213031250, 208.33333333333, 12.5, '2024-05-13 00:00:00', '2023-12-13 09:29:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051238, 20231213031250, 208.33333333333, 12.5, '2024-06-13 00:00:00', '2023-12-13 09:29:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051239, 20231213031250, 208.33333333333, 12.5, '2024-07-13 00:00:00', '2023-12-13 09:29:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051240, 20231213031250, 208.33333333333, 12.5, '2024-08-13 00:00:00', '2023-12-13 09:38:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051241, 20231213031250, 208.33333333333, 12.5, '2024-09-13 00:00:00', '2023-12-13 09:44:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051242, 20231213031250, 208.33333333333, 12.5, '2024-10-13 00:00:00', '2023-12-13 09:53:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051243, 20231213031250, 208.33333333333, 12.5, '2024-11-13 00:00:00', '2023-12-13 09:53:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051244, 20231213031250, 208.33333333333, 12.5, '2024-12-13 00:00:00', '2023-12-13 09:53:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051245, 20231219191248, 33.3, 1.665, '2024-01-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051246, 20231219191248, 33.3, 1.665, '2024-02-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051247, 20231219191248, 33.3, 1.665, '2024-03-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051248, 20231219191248, 33.3, 1.665, '2024-04-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051249, 20231219191248, 33.3, 1.665, '2024-05-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051250, 20231219191248, 33.3, 1.665, '2024-06-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051251, 20231219191248, 33.3, 1.665, '2024-07-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051252, 20231219191248, 33.3, 1.665, '2024-08-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051253, 20231219191248, 33.3, 1.665, '2024-09-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051254, 20231219191248, 33.3, 1.665, '2024-10-19 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051255, 20231226111212, 33.3, 1.665, '2024-01-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051256, 20231226111212, 33.3, 1.665, '2024-02-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051257, 20231226111212, 33.3, 1.665, '2024-03-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051258, 20231226111212, 33.3, 1.665, '2024-04-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051259, 20231226111212, 33.3, 1.665, '2024-05-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051260, 20231226111212, 33.3, 1.665, '2024-06-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051261, 20231226111212, 33.3, 1.665, '2024-07-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051262, 20231226111212, 33.3, 1.665, '2024-08-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051263, 20231226111212, 33.3, 1.665, '2024-09-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051264, 20231226111212, 33.3, 1.665, '2024-10-26 00:00:00', '2023-12-26 11:27:00', -2);
INSERT INTO `tbldepreciation` VALUES (20231212051265, 20231227091252, 10.090909090909, 12.21, '2024-01-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051266, 20231227091252, 10.090909090909, 12.21, '2024-02-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051267, 20231227091252, 10.090909090909, 12.21, '2024-03-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051268, 20231227091252, 10.090909090909, 12.21, '2024-04-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051269, 20231227091252, 10.090909090909, 12.21, '2024-05-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051270, 20231227091252, 10.090909090909, 12.21, '2024-06-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051271, 20231227091252, 10.090909090909, 12.21, '2024-07-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051272, 20231227091252, 10.090909090909, 12.21, '2024-08-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051273, 20231227091252, 10.090909090909, 12.21, '2024-09-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051274, 20231227091252, 10.090909090909, 12.21, '2024-10-27 00:00:00', NULL, NULL);
INSERT INTO `tbldepreciation` VALUES (20231212051275, 20231227091252, 10.090909090909, 12.21, '2024-11-27 00:00:00', NULL, NULL);

-- ----------------------------
-- Table structure for tblusername
-- ----------------------------
DROP TABLE IF EXISTS `tblusername`;
CREATE TABLE `tblusername`  (
  `userid` bigint NOT NULL,
  `username` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `userpassword` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `Photo` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`userid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tblusername
-- ----------------------------
INSERT INTO `tblusername` VALUES (100, 'K\'jen', '999', 'excel/7.jpeg');
INSERT INTO `tblusername` VALUES (101, 'Admin', '123', 'excel/17.jpeg');
INSERT INTO `tblusername` VALUES (102, 'Thea', '001', 'excel/13.jpeg');
INSERT INTO `tblusername` VALUES (20231205051214, 'Levimis', '55555', '34.jpeg');
INSERT INTO `tblusername` VALUES (20231206031226, 'Ah jm', '55555', '4.jpeg');

-- ----------------------------
-- View structure for view_customer_paid
-- ----------------------------
DROP VIEW IF EXISTS `view_customer_paid`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_customer_paid` AS SELECT
	tblcustomer.cusid, 
	tblcustomer.cusname, 
	tblcustomer.custel, 
	tblcustomer.cusAddress, 
	tblcustomer.Photo, 
	tbldepreciation.Principal, 
	tbldepreciation.Interest_Month, 
	tbldepreciation.Paid_Date, 
	tbldepreciation.Clear_Date, 
	tbldepreciation.clear_by_userid
FROM
	tblcustomer
	INNER JOIN
	tbldepreciation
	ON 
		tblcustomer.cusid = tbldepreciation.cusid ;

SET FOREIGN_KEY_CHECKS = 1;
