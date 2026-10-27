-- Migration: 004_create_views.sql
-- Description: Creates the customer paid schedules view

CREATE OR REPLACE VIEW `view_customer_paid` AS 
SELECT 
  `tblcustomer`.`cusid` AS `cusid`,
  `tblcustomer`.`cusname` AS `cusname`,
  `tblcustomer`.`custel` AS `custel`,
  `tblcustomer`.`cusAddress` AS `cusAddress`,
  `tblcustomer`.`Photo` AS `Photo`,
  `tbldepreciation`.`Principal` AS `Principal`,
  `tbldepreciation`.`Interest_Month` AS `Interest_Month`,
  `tbldepreciation`.`Paid_Date` AS `Paid_Date`,
  `tbldepreciation`.`Clear_Date` AS `Clear_Date`,
  `tbldepreciation`.`clear_by_userid` AS `clear_by_userid` 
FROM (`tblcustomer` JOIN `tbldepreciation` ON ((`tblcustomer`.`cusid` = `tbldepreciation`.`cusid`)));
