<?php
declare(strict_types=1);

include_once("auth.php");
include_once("connection.php");

$Depreid = isset($_GET['Depreid']) ? (int)$_GET['Depreid'] : 0;
$cusid = isset($_GET['cusid']) ? (int)$_GET['cusid'] : 0;
$userId = isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : -2;

if ($Depreid > 0) {
    $now = date("Y-m-d H:i:s");
    $conn->execute_query(
        "UPDATE tbldepreciation SET Clear_Date = ?, clear_by_userid = ? WHERE Depreid = ?",
        [$now, $userId, $Depreid]
    );
}

header('Location: depreciation.php?param=' . $cusid);
exit();
