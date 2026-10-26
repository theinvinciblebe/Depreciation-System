<?php
declare(strict_types=1);

include_once("auth.php");
include_once("connection.php");
include_once("lang.php");

$param = isset($_GET['param']) ? (int)$_GET['param'] : 0;
$result = $conn->execute_query("SELECT * FROM tblcustomer WHERE cusid = ? LIMIT 1", [$param]);

if ($result && $row = $result->fetch_assoc()) {
    $Getcusname = (string)($row['cusname'] ?? '');
    $Getcutel = (string)($row['custel'] ?? '');
    $GetIDCard = (string)($row['IDCard'] ?? '');
    $GetcusAddress = (string)($row['cusAddress'] ?? '');
    $GetcusPhoto = (string)($row['Photo'] ?? '');
    $GetPName = (string)($row['ProductName'] ?? '');
    $GetcPPrice = (string)($row['ProductPrice'] ?? '0');
    $GetcInterest = (string)($row['Interest'] ?? '0');
    $GetcDuration = (string)($row['Duration'] ?? '0');
    $GetCreate_Date = (string)($row['Create_Date'] ?? '');
    $GetUserid = (string)($row['Userid'] ?? '');
} else {
    header('Location: customer.php');
    exit();
}

$errorMsg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsave'])) {
    $txtcusname = trim((string)($_POST['txtcusname'] ?? ''));
    $txttel = trim((string)($_POST['txttel'] ?? ''));
    $txtIDCard = trim((string)($_POST['txtIDCard'] ?? ''));
    $txtcusaddress = trim((string)($_POST['txtcusaddress'] ?? ''));
    $txtPname = trim((string)($_POST['txtPname'] ?? ''));
    $txtPprice = max(0.0, (float)($_POST['txtPprice'] ?? 0));
    $txtInterest = max(0.0, (float)($_POST['txtInterest'] ?? 0));
    $txtDuration = max(0, (int)($_POST['txtDuration'] ?? 0));
    $Createdate = date('Y-m-d H:i:s');
    $txtUserid = !empty($_POST['txtuserid']) ? (int)$_POST['txtuserid'] : (int)($_SESSION['uid'] ?? 1);

    $imgname = $GetcusPhoto;
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
            "UPDATE tblcustomer SET 
                cusname = ?,
                custel = ?,
                IDCard = ?,
                cusAddress = ?,
                Photo = ?,
                ProductName = ?,
                ProductPrice = ?,
                Interest = ?,
                Duration = ?,
                Create_Date = ?,
                Userid = ?
            WHERE cusid = ?",
            [$txtcusname, $txttel, $txtIDCard, $txtcusaddress, $imgname, $txtPname, $txtPprice, $txtInterest, $txtDuration, $Createdate, $txtUserid, $param]
        );

        if ($updated) {
            header('Location: customer.php');
            exit();
        } else {
            $errorMsg = "Failed to update: " . $conn->error;
        }
    } catch (\Throwable $e) {
        $errorMsg = "Database error: " . $e->getMessage();
    }
}

include_once("Head.php");
include_once("Navbar.php");
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="customer.php" class="text-secondary text-decoration-none">
                    <i class="fa-solid fa-arrow-left"></i> <?php echo __('cust_title'); ?>
                </a>
                <span class="text-muted">/</span>
                <span class="text-primary fw-semibold"><?php echo __('cust_edit_title'); ?> #<?php echo $param; ?></span>
            </div>
            <h2 class="fw-bold mb-0"><?php echo __('cust_edit_title'); ?></h2>
        </div>
        <a href="customer.php" class="btn btn-modern-outline">
            <i class="fa-solid fa-xmark"></i> <?php echo __('cancel'); ?>
        </a>
    </div>

    <?php if (isset($errorMsg)) { ?>
        <div class="alert alert-danger shadow-sm border-0 mb-4"><?php echo $errorMsg; ?></div>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <div class="row g-4">
            <!-- Left Column: Personal Information -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="fa-solid fa-id-card text-primary me-2"></i><?php echo __('cust_personal_info'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_full_name'); ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="txtcusname" class="form-control border-start-0" value="<?php echo htmlspecialchars($Getcusname); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_phone'); ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-muted"></i></span>
                                <input type="text" name="txttel" class="form-control border-start-0" value="<?php echo htmlspecialchars($Getcutel); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_id_card'); ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-address-card text-muted"></i></span>
                                <input type="text" name="txtIDCard" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetIDCard); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_address'); ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-location-dot text-muted"></i></span>
                                <input type="text" name="txtcusaddress" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetcusAddress); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_choose_photo'); ?></label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <?php if (!empty($GetcusPhoto) && file_exists(dirname(__FILE__) . '/images/' . $GetcusPhoto)) { ?>
                                    <img src="images/<?php echo htmlspecialchars($GetcusPhoto); ?>" class="avatar-img" style="width: 56px; height: 56px;" alt="Current Photo">
                                <?php } ?>
                                <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
                            </div>
                            <small class="text-muted"><?php echo __('cust_photo_leave_blank'); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Product & Terms -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="fa-solid fa-calculator text-primary me-2"></i><?php echo __('cust_terms_title'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_product_name'); ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-box-open text-muted"></i></span>
                                <input type="text" name="txtPname" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetPName); ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo __('cust_product_price'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-dollar-sign text-muted"></i></span>
                                    <input type="number" step="0.01" name="txtPprice" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetcPPrice); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo __('cust_interest'); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-percent text-muted"></i></span>
                                    <input type="number" step="0.01" name="txtInterest" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetcInterest); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo __('cust_duration'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-days text-muted"></i></span>
                                    <input type="number" name="txtDuration" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetcDuration); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo __('cust_user_id'); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user-gear text-muted"></i></span>
                                    <input type="text" name="txtuserid" class="form-control border-start-0" value="<?php echo htmlspecialchars($GetUserid); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 mt-4" style="background: rgba(99, 102, 241, 0.08); border: 1px dashed rgba(99, 102, 241, 0.3);">
                            <div class="d-flex align-items-center gap-2 mb-1 text-primary fw-bold">
                                <i class="fa-solid fa-circle-info"></i> <?php echo __('cust_note_update'); ?>
                            </div>
                            <small class="text-muted">
                                <?php echo __('cust_note_update_desc'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="col-12">
                <div class="card shadow-sm border-0 p-3">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <a href="customer.php" class="btn btn-secondary px-4"><?php echo __('cancel'); ?></a>
                        <button type="submit" name="btnsave" class="btn btn-modern-primary px-4">
                            <i class="fa-solid fa-floppy-disk"></i> <?php echo __('save_changes'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
include("footer.php");
?>