<?php
ob_start();
include("Head.php");
include("Navbar.php");

$param = isset($_GET['param']) ? (int)$_GET['param'] : 0;
$sqlGet = "SELECT * FROM tblcustomer WHERE cusid = $param";
$result = $conn->query($sqlGet);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Getcusname = $row['cusname'];
    $Getcutel = $row['custel'];
    $GetIDCard = $row['IDCard'];
    $GetcusAddress = $row['cusAddress'];
    $GetcusPhoto = $row['Photo'];
    $GetPName = $row['ProductName'];
    $GetcPPrice = $row['ProductPrice'];
    $GetcInterest = $row['Interest'];
    $GetcDuration = $row['Duration'];
    $GetCreate_Date = $row['Create_Date'];
    $GetUserid = $row['Userid'];
} else {
    header('Location: customer.php');
    exit();
}

if (isset($_POST['btnsave'])) {
    $txtcusname = $_POST['txtcusname'];
    $txttel = $_POST['txttel'];
    $txtIDCard = $_POST['txtIDCard'];
    $txtcusaddress = $_POST['txtcusaddress'];
    $txtPname = $_POST['txtPname'];
    $txtPprice = (float)$_POST['txtPprice'];
    $txtInterest = (float)$_POST['txtInterest'];
    $txtDurartion = (int)$_POST['txtDuration'];
    $Createdate = date('Y-m-d H:i:s');
    $txtUserid = $_POST['txtuserid'];

    $imgname = $GetcusPhoto;
    if (!empty($_FILES['fileToUpload']['name'])) {
        $uploaddir = getcwd() . '/images/';
        $imgname = time() . '_' . basename($_FILES['fileToUpload']['name']);
        $uploadfile = $uploaddir . $imgname;
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile);
    }

    $sql = "UPDATE tblcustomer SET 
                cusname = '$txtcusname',
                custel = '$txttel',
                IDCard = '$txtIDCard',
                cusAddress = '$txtcusaddress',
                Photo = '$imgname',
                ProductName = '$txtPname',
                ProductPrice = '$txtPprice',
                Interest = '$txtInterest',
                Duration = '$txtDurartion',
                Create_Date = '$Createdate',
                Userid = '$txtUserid'
            WHERE cusid = $param";

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        header('Location: customer.php');
        exit();
    } else {
        $errorMsg = "Error: " . $sql . "<br>" . $conn->error;
    }
}
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