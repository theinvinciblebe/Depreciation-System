<?php
declare(strict_types=1);

include_once("auth.php");
include_once("connection.php");
include_once("lang.php");

$errorMsg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsave'])) {
    $autoid = (int)date('YmdHis');
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

    $imgname = '';
    if (!empty($_FILES['fileToUpload']['name']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
        $uploaddir = __DIR__ . '/images/';
        if (!is_dir($uploaddir)) {
            @mkdir($uploaddir, 0755, true);
        }
        $ext = strtolower(pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($ext, $allowed, true)) {
            $candidateName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            if (@move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploaddir . $candidateName)) {
                $imgname = $candidateName;
            }
        }
    }

    try {
        $insertCust = $conn->execute_query(
            "INSERT INTO tblcustomer (cusid, cusname, custel, IDCard, cusAddress, Photo, ProductName, ProductPrice, Interest, Duration, Create_Date, Userid)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$autoid, $txtcusname, $txttel, $txtIDCard, $txtcusaddress, $imgname, $txtPname, $txtPprice, $txtInterest, $txtDuration, $Createdate, $txtUserid]
        );

        if ($insertCust) {
            if ($txtDuration > 0) {
                $principal = round($txtPprice / $txtDuration, 2);
                $interest_month = round(($txtPprice * $txtInterest) / 100, 2);
                $baseDate = date("Y-m-d");

                $stmtDepre = $conn->prepare("INSERT INTO tbldepreciation (cusid, Principal, Interest_Month, Paid_Date, Clear_Date, clear_by_userid) VALUES (?, ?, ?, ?, NULL, NULL)");
                for ($i = 1; $i <= $txtDuration; $i++) {
                    $post_date = date('Y-m-d 00:00:00', strtotime("+$i months", strtotime($baseDate)));
                    $stmtDepre->bind_param("idds", $autoid, $principal, $interest_month, $post_date);
                    $stmtDepre->execute();
                }
            }
            header('Location: customer.php');
            exit();
        } else {
            $errorMsg = "Failed to insert customer: " . $conn->error;
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
                <span class="text-primary fw-semibold"><?php echo __('cust_create_title'); ?></span>
            </div>
            <h2 class="fw-bold mb-0"><?php echo __('cust_create_title'); ?></h2>
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
            <!-- Left Column: Customer Personal Details -->
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
                                <input type="text" name="txtcusname" class="form-control border-start-0" placeholder="e.g. John Doe" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_phone'); ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-muted"></i></span>
                                <input type="text" name="txttel" class="form-control border-start-0" placeholder="e.g. 012 345 678" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_id_card'); ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-address-card text-muted"></i></span>
                                <input type="text" name="txtIDCard" class="form-control border-start-0" placeholder="e.g. 010203040">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_address'); ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-location-dot text-muted"></i></span>
                                <input type="text" name="txtcusaddress" class="form-control border-start-0" placeholder="Street, Khan, City">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo __('cust_choose_photo'); ?></label>
                            <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
                            <small class="text-muted"><?php echo __('cust_photo_hint'); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Product & Depreciation Terms -->
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
                                <input type="text" name="txtPname" class="form-control border-start-0" placeholder="e.g. Honda Dream 2024" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo __('cust_product_price'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-dollar-sign text-muted"></i></span>
                                    <input type="number" step="0.01" name="txtPprice" id="txtPprice" class="form-control border-start-0" placeholder="2500.00" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo __('cust_interest'); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-percent text-muted"></i></span>
                                    <input type="number" step="0.01" name="txtInterest" id="txtInterest" class="form-control border-start-0" placeholder="1.5" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo __('cust_duration'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-days text-muted"></i></span>
                                    <input type="number" name="txtDuration" id="txtDuration" class="form-control border-start-0" placeholder="12" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo __('cust_user_id'); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user-gear text-muted"></i></span>
                                    <input type="text" name="txtuserid" class="form-control border-start-0" value="<?php echo isset($_SESSION['uid']) ? $_SESSION['uid'] : '1'; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 mt-4" style="background: rgba(99, 102, 241, 0.08); border: 1px dashed rgba(99, 102, 241, 0.3);">
                            <div class="d-flex align-items-center gap-2 mb-1 text-primary fw-bold">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> <?php echo __('cust_auto_amort'); ?>
                            </div>
                            <small class="text-muted">
                                <?php echo __('cust_auto_amort_desc'); ?>
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
                        <button type="submit" name="btnsave" class="btn btn-modern-success px-4">
                            <i class="fa-solid fa-check"></i> <?php echo __('cust_btn_save_generate'); ?>
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