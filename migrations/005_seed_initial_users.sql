-- Migration: 005_seed_initial_users.sql
-- Description: Seeds initial administrator and staff accounts
-- Default credentials:
-- 1. Admin   / 123   (System Administrator)
-- 2. Thea    / 001   (Branch Manager)
-- 3. Sopheap / 1234  (Loan Officer)

INSERT INTO `tblusername` (`userid`, `username`, `userpassword`, `Photo`) VALUES 
(101, 'Admin', '$2y$10$rxZFK/MSRtCNNJlpIzhkbuPB3/JO/fhQKLI8YueBWPiyZDiIu65Ni', 's1.jpg'),
(102, 'Thea', '$2y$10$04ppZ2fsbvmljdcXxpo0aOcnvVsHLunwCA8xH8mkGt/A51uDFakTi', 's2.jpg'),
(103, 'Sopheap', '$2y$10$KHcQy7.ORysto7gCzrI.AuOPqGzL5VBrjpqbHT7/2rzZip8Igyki.', 's3.jpg')
ON DUPLICATE KEY UPDATE 
  `username` = VALUES(`username`),
  `userpassword` = VALUES(`userpassword`),
  `Photo` = VALUES(`Photo`);
