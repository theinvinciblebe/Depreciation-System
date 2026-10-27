-- Migration: 006_seed_sample_customers.sql
-- Description: Seeds realistic demonstration customers and dynamic amortization schedules

-- Clean existing sample data
DELETE FROM `tbldepreciation`;
DELETE FROM `tblcustomer`;

-- -------------------------------------------------------------
-- 1. Insert Customers
-- -------------------------------------------------------------
INSERT INTO `tblcustomer` 
  (`cusid`, `cusname`, `custel`, `IDCard`, `cusAddress`, `Photo`, `ProductName`, `ProductPrice`, `Interest`, `Duration`, `Create_Date`, `Userid`) 
VALUES 
  -- Customer 1: Sokha Chan (Active loan, payment due in 5 days -> Report 1 Due Collections)
  (20261001000001, 'Sokha Chan', '012 889 977', '010293847', 'Phnom Penh, Toul Kork', 'iphone15.jpg', 'iPhone 15 Pro Max 256GB', 1500.00, 1.5, 6, DATE_SUB(NOW(), INTERVAL 65 DAY), 101),

  -- Customer 2: Bopha Vong (Active loan, payment overdue by 15 days -> Report 2 Overdue)
  (20261001000002, 'Bopha Vong', '098 776 543', '029384756', 'Siem Reap, Svay Dangkum', 'Mac.jpg', 'MacBook Air M2 13-inch', 1200.00, 1.2, 6, DATE_SUB(NOW(), INTERVAL 75 DAY), 102),

  -- Customer 3: Dara Samnang (Active loan, payment due in 9 days -> Report 1 Due Collections)
  (20261001000003, 'Dara Samnang', '088 554 433', '038475629', 'Battambang, Romchek 4', 'Rog.png', 'ASUS ROG Zephyrus G14', 1800.00, 1.0, 6, DATE_SUB(NOW(), INTERVAL 40 DAY), 101),

  -- Customer 4: Linda Chen (All installments fully settled -> Report 4 Settled Loans)
  (20261001000004, 'Linda Chen', '077 123 456', '047586930', 'Phnom Penh, Chamkarmon', 'Ipad.jpg', 'iPad Pro 11-inch M2', 900.00, 1.5, 3, DATE_SUB(NOW(), INTERVAL 120 DAY), 101),

  -- Customer 5: Mengly Kong (Active loan, first installment in 20 days -> Report 3 Pending Loans)
  (20261001000005, 'Mengly Kong', '093 321 654', '058493021', 'Kandal, Takhmao City', 's1.jpg', 'Sony PlayStation 5 Slim', 600.00, 2.0, 4, DATE_SUB(NOW(), INTERVAL 10 DAY), 103);

-- -------------------------------------------------------------
-- 2. Insert Depreciation Schedules
-- -------------------------------------------------------------

-- Customer 1 Schedules ($250 Principal + $22.50 Interest)
INSERT INTO `tbldepreciation` (`cusid`, `Principal`, `Interest_Month`, `Paid_Date`, `Clear_Date`, `clear_by_userid`) VALUES
  (20261001000001, 250.00, 22.50, DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 34 DAY), 101),
  (20261001000001, 250.00, 22.50, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), 101),
  (20261001000001, 250.00, 22.50, DATE_ADD(NOW(), INTERVAL 5 DAY), NULL, NULL),
  (20261001000001, 250.00, 22.50, DATE_ADD(NOW(), INTERVAL 35 DAY), NULL, NULL),
  (20261001000001, 250.00, 22.50, DATE_ADD(NOW(), INTERVAL 65 DAY), NULL, NULL),
  (20261001000001, 250.00, 22.50, DATE_ADD(NOW(), INTERVAL 95 DAY), NULL, NULL);

-- Customer 2 Schedules ($200 Principal + $14.40 Interest)
INSERT INTO `tbldepreciation` (`cusid`, `Principal`, `Interest_Month`, `Paid_Date`, `Clear_Date`, `clear_by_userid`) VALUES
  (20261001000002, 200.00, 14.40, DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 44 DAY), 102),
  (20261001000002, 200.00, 14.40, DATE_SUB(NOW(), INTERVAL 15 DAY), NULL, NULL),
  (20261001000002, 200.00, 14.40, DATE_ADD(NOW(), INTERVAL 15 DAY), NULL, NULL),
  (20261001000002, 200.00, 14.40, DATE_ADD(NOW(), INTERVAL 45 DAY), NULL, NULL),
  (20261001000002, 200.00, 14.40, DATE_ADD(NOW(), INTERVAL 75 DAY), NULL, NULL),
  (20261001000002, 200.00, 14.40, DATE_ADD(NOW(), INTERVAL 105 DAY), NULL, NULL);

-- Customer 3 Schedules ($300 Principal + $18.00 Interest)
INSERT INTO `tbldepreciation` (`cusid`, `Principal`, `Interest_Month`, `Paid_Date`, `Clear_Date`, `clear_by_userid`) VALUES
  (20261001000003, 300.00, 18.00, DATE_SUB(NOW(), INTERVAL 21 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY), 101),
  (20261001000003, 300.00, 18.00, DATE_ADD(NOW(), INTERVAL 9 DAY), NULL, NULL),
  (20261001000003, 300.00, 18.00, DATE_ADD(NOW(), INTERVAL 39 DAY), NULL, NULL),
  (20261001000003, 300.00, 18.00, DATE_ADD(NOW(), INTERVAL 69 DAY), NULL, NULL),
  (20261001000003, 300.00, 18.00, DATE_ADD(NOW(), INTERVAL 99 DAY), NULL, NULL),
  (20261001000003, 300.00, 18.00, DATE_ADD(NOW(), INTERVAL 129 DAY), NULL, NULL);

-- Customer 4 Schedules ($300 Principal + $13.50 Interest - All Cleared)
INSERT INTO `tbldepreciation` (`cusid`, `Principal`, `Interest_Month`, `Paid_Date`, `Clear_Date`, `clear_by_userid`) VALUES
  (20261001000004, 300.00, 13.50, DATE_SUB(NOW(), INTERVAL 90 DAY), DATE_SUB(NOW(), INTERVAL 89 DAY), 101),
  (20261001000004, 300.00, 13.50, DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 59 DAY), 101),
  (20261001000004, 300.00, 13.50, DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 29 DAY), 102);

-- Customer 5 Schedules ($150 Principal + $12.00 Interest)
INSERT INTO `tbldepreciation` (`cusid`, `Principal`, `Interest_Month`, `Paid_Date`, `Clear_Date`, `clear_by_userid`) VALUES
  (20261001000005, 150.00, 12.00, DATE_ADD(NOW(), INTERVAL 20 DAY), NULL, NULL),
  (20261001000005, 150.00, 12.00, DATE_ADD(NOW(), INTERVAL 50 DAY), NULL, NULL),
  (20261001000005, 150.00, 12.00, DATE_ADD(NOW(), INTERVAL 80 DAY), NULL, NULL),
  (20261001000005, 150.00, 12.00, DATE_ADD(NOW(), INTERVAL 110 DAY), NULL, NULL);
