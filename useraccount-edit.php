<?php
declare(strict_types=1);

include_once("auth.php");
include_once("connection.php");
include_once("lang.php");

$param = isset($_GET['param']) ? (int)$_GET['param'] : 0;
$result = $conn->execute_query("SELECT * FROM tblusername WHERE userid = ? LIMIT 1", [$param]);

if ($result && $row = $result->fetch_assoc()) {
    $GetUsername = (string)($row['username'] ?? '');
    $GetPassword = (string)($row['userpassword'] ?? '');
    $Getphoto = (string)($row['Photo'] ?? '');
} else {
    header('Location: UserAccount.php');
    exit();
}

$errorMsg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsave'])) {
    $txtname = trim((string)($_POST['txtUsername'] ?? ''));
    $txtpass = (string)($_POST['txtpassword'] ?? '');

    if ($txtname === '') {
        $errorMsg = "Username cannot be empty.";
    } else {
        // If password changed or is plaintext, hash it; otherwise keep existing hash
        $finalPassword = $GetPassword;
        if ($txtpass !== '' && $txtpass !== $GetPassword) {
            $finalPassword = password_hash($txtpass, PASSWORD_DEFAULT);
        }

        $imgname = $Getphoto;
        if (!empty($_FILES['fileToUpload']['name']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
            $uploaddir = __DIR__ . '/images/';
            if (!is_dir($uploaddir)) {
                mkdir($uploaddir, 0755, true);
            }
            $ext = strtolower(pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed, true)) {
                $imgname = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploaddir . $imgname);
            }
        }

        try {
            $updated = $conn->execute_query(
                "UPDATE tblusername SET username = ?, userpassword = ?, Photo = ? WHERE userid = ?",
                [$txtname, $finalPassword, $imgname, $param]
            );

            if ($updated) {
                // If editing self, update active session username
                if (isset($_SESSION['uid']) && (int)$_SESSION['uid'] === $param) {
                    $_SESSION['uName'] = $txtname;
                }
                header('Location: UserAccount.php');
                exit();
            } else {
                $errorMsg = "Failed to update: " . $conn->error;
            }
        } catch (\Throwable $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

include_once("Head.php");
include_once("Navbar.php");
?>

<div class="container py-4" style="max-width: 680px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="UserAccount.php" class="text-secondary text-decoration-none">
                    <i class="fa-solid fa-arrow-left"></i> <?php echo __('user_title'); ?>
                </a>
                <span class="text-muted">/</span>
                <span class="text-primary fw-semibold"><?php echo __('user_edit_title'); ?> #<?php echo $param; ?></span>
            </div>
            <h2 class="fw-bold mb-0"><?php echo __('user_edit_title'); ?></h2>
        </div>
        <a href="UserAccount.php" class="btn btn-modern-outline">
            <i class="fa-solid fa-xmark"></i> <?php echo __('cancel'); ?>
        </a>
    </div>

    <?php if (isset($errorMsg)) { ?>
        <div class="alert alert-danger shadow-sm border-0 mb-4"><?php echo $errorMsg; ?></div>
    <?php } ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent">
            <h5 class="card-title mb-0">
                <i class="fa-solid fa-user-gear text-primary me-2"></i><?php echo __('user_credentials'); ?>
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label"><?php echo __('user_username'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                        <input type="text" name="txtUsername" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetUsername); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><?php echo __('user_password'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="txtpassword" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetPassword); ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label"><?php echo __('user_avatar'); ?></label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <?php if (!empty($Getphoto) && file_exists(dirname(__FILE__) . '/images/' . $Getphoto)) { ?>
                            <img src="images/<?php echo htmlspecialchars($Getphoto); ?>" class="avatar-img" style="width: 56px; height: 56px;" alt="User Avatar">
                        <?php } ?>
                        <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
                    </div>
                    <small class="text-muted"><?php echo __('cust_photo_leave_blank'); ?></small>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                    <a href="UserAccount.php" class="btn btn-secondary px-4"><?php echo __('cancel'); ?></a>
                    <button type="submit" name="btnsave" class="btn btn-modern-primary px-4">
                        <i class="fa-solid fa-floppy-disk"></i> <?php echo __('save_changes'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>