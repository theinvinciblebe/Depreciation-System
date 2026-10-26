<?php
declare(strict_types=1);

include_once("auth.php");
include_once("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = isset($_POST['userid']) ? (int)$_POST['userid'] : 0;
    $currentUid = isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : 0;

    // Prevent deleting own account to avoid being locked out
    if ($userid > 0 && $userid !== $currentUid) {
        $resPhoto = $conn->execute_query("SELECT Photo FROM tblusername WHERE userid = ? LIMIT 1", [$userid]);
        if ($resPhoto && $row = $resPhoto->fetch_assoc()) {
            $photo = (string)($row['Photo'] ?? '');
            if ($photo !== '' && file_exists(__DIR__ . '/images/' . $photo)) {
                @unlink(__DIR__ . '/images/' . $photo);
            }
        }

        $conn->execute_query("DELETE FROM tblusername WHERE userid = ?", [$userid]);
    }

    header("Location: UserAccount.php?deleted=1");
    exit();
}

header("Location: UserAccount.php");
exit();