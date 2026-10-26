<?php
declare(strict_types=1);

include_once("auth.php");
include_once("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cusid = isset($_POST['cusid']) ? (int)$_POST['cusid'] : 0;

    if ($cusid > 0) {
        // Fetch photo to unlink if exists
        $resPhoto = $conn->execute_query("SELECT Photo FROM tblcustomer WHERE cusid = ? LIMIT 1", [$cusid]);
        if ($resPhoto && $row = $resPhoto->fetch_assoc()) {
            $photo = (string)($row['Photo'] ?? '');
            if ($photo !== '' && file_exists(__DIR__ . '/images/' . $photo)) {
                @unlink(__DIR__ . '/images/' . $photo);
            }
        }

        // Clean up associated depreciation rows and customer record
        $conn->execute_query("DELETE FROM tbldepreciation WHERE cusid = ?", [$cusid]);
        $conn->execute_query("DELETE FROM tblcustomer WHERE cusid = ?", [$cusid]);
    }

    header("Location: customer.php?deleted=1");
    exit();
}

header("Location: customer.php");
exit();
