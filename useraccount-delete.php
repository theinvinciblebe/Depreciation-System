<?php
include("head.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userid = (int)$_POST['userid'];

    $stmt = $conn->prepare("DELETE FROM tblusername WHERE userid = ?");
    $stmt->bind_param("i", $userid);

    if ($stmt->execute()) {
        header("Location: UserAccount.php");
        exit();
    } else {
        echo "Delete failed: " . $conn->error;
    }
}
?>