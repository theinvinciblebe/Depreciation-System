<?php
ob_start();
include("Head.php");
include("Navbar.php");

$param = isset($_GET['param']) ? (int)$_GET['param'] : 0;
$sqlGet = "SELECT * FROM tblusername WHERE userid = $param";
$result = $conn->query($sqlGet);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $GetUsername = $row['username'];
    $GetPassword = $row['userpassword'];
    $Getphoto = $row['Photo'];
} else {
    header('Location: UserAccount.php');
    exit();
}

if (isset($_POST['btnsave'])) {
    $txtname = $_POST['txtUsername'];
    $txtpass = $_POST['txtpassword'];

    $imgname = $Getphoto;
    if (!empty($_FILES['fileToUpload']['name'])) {
        $uploaddir = getcwd() . '/images/';
        $imgname = time() . '_' . basename($_FILES['fileToUpload']['name']);
        $uploadfile = $uploaddir . $imgname;
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile);
    }

    $sql = "UPDATE tblusername SET 
                username = '$txtname',
                userpassword = '$txtpass',
                Photo = '$imgname'
            WHERE userid = $param";

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        header('Location: UserAccount.php');
        exit();
    } else {
        $errorMsg = "Error: " . $sql . "<br>" . $conn->error;
    }
}
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