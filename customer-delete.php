<?php
include("head.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $cusid = (int)$_POST['cusid'];

    $stmt = $conn->prepare("DELETE FROM tblcustomer WHERE cusid = ?");
    $stmt->bind_param("i", $cusid);

    if ($stmt->execute()) {
        header("Location: customer.php");
        exit();
    } else {
        echo "Delete failed: " . $conn->error;
    }
}
?>

