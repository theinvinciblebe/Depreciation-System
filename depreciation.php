<?php
include("Head.php");
include("Navbar.php");

$param = 0;
if (isset($_GET['param'])) {
    $param = (int)$_GET['param'];
}

// Fetch Customer Details
$customerName = 'Customer';
$productName = 'Asset';
$productPrice = 0;
$sqlCustomer = "SELECT * FROM tblcustomer WHERE cusid = $param";
$resCustomer = $conn->query($sqlCustomer);
if ($resCustomer && $cRow = $resCustomer->fetch_assoc()) {
    $customerName = $cRow['cusname'];
    $productName = $cRow['ProductName'];
    $productPrice = $cRow['ProductPrice'];
}

// Query Depreciation entries
$sql = "SELECT *, if(clear_by_userid, 'Paid', 'Pending') as statusPaid FROM tbldepreciation WHERE cusid = $param ORDER BY Paid_Date ASC";
$result = $conn->query($sql);

$rows = [];
$totalPending = 0;
$totalPaid = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
        if (is_null($row['clear_by_userid'])) {
            $totalPending += (float)$row['Principal'] + (float)$row['Interest_Month'];
        } else {
            $totalPaid += (float)$row['Principal'] + (float)$row['Interest_Month'];
        }
    }
}

$grandTotal = $totalPaid + $totalPending;
$progressPercent = $grandTotal > 0 ? round(($totalPaid / $grandTotal) * 100) : 0;
?>

<div class="container-fluid py-4 px-md-4">
    <!-- Breadcrumb & Customer Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="customer.php" class="text-secondary text-decoration-none">
                    <i class="fa-solid fa-arrow-left"></i> <?php echo __('cust_title'); ?>
                </a>
                <span class="text-muted">/</span>
                <span class="text-primary fw-semibold"><?php echo __('depre_title'); ?></span>
            </div>
            <h2 class="mb-0 fw-bold">
                <?php echo htmlspecialchars($customerName); ?> 
                <span class="badge bg-primary-subtle text-primary fs-6 fw-normal ms-2"><?php echo __('depre_asset'); ?>: <?php echo htmlspecialchars($productName); ?></span>
            </h2>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="customer.php" class="btn btn-modern-outline">
                <i class="fa-solid fa-users"></i> <?php echo __('back_to_list'); ?>
            </a>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card stat-success mb-0 h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label"><?php echo __('depre_total_paid'); ?></div>
                        <div class="stat-value text-success">$<?php echo number_format($totalPaid, 2); ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span><?php echo __('depre_cleared_amort'); ?></span>
                    <span class="badge bg-success-subtle text-success"><?php echo $progressPercent; ?>% <?php echo __('depre_repaid'); ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card stat-danger mb-0 h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label"><?php echo __('depre_total_pending'); ?></div>
                        <div class="stat-value text-danger">$<?php echo number_format($totalPending, 2); ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span><?php echo __('depre_remaining_desc'); ?></span>
                    <span class="badge bg-danger-subtle text-danger"><?php echo 100 - $progressPercent; ?>% <?php echo __('depre_remaining'); ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card stat-primary mb-0 h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label"><?php echo __('depre_total_repay'); ?></div>
                        <div class="stat-value">$<?php echo number_format($grandTotal, 2); ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span><?php echo __('depre_progress'); ?></span>
                    <div class="w-50">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $progressPercent; ?>%;" aria-valuenow="<?php echo $progressPercent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">
                <i class="fa-solid fa-calendar-check text-primary me-2"></i><?php echo __('depre_install_sched'); ?>
            </h5>
            <span class="badge bg-light text-secondary border px-3 py-2">
                <?php echo __('depre_total_installments'); ?>: <?php echo count($rows); ?>
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr align="center">
                            <th scope="col" style="width: 70px;">#</th>
                            <th scope="col"><?php echo __('depre_th_due_date'); ?></th>
                            <th scope="col"><?php echo __('depre_th_principal'); ?></th>
                            <th scope="col"><?php echo __('depre_th_interest'); ?></th>
                            <th scope="col"><?php echo __('depre_th_total_due'); ?></th>
                            <th scope="col"><?php echo __('depre_th_status'); ?></th>
                            <th scope="col" style="width: 170px;"><?php echo __('depre_th_action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($rows)) {
                        $i = 1;
                        foreach ($rows as $row) {
                            $isPaid = ($row['statusPaid'] === 'Paid');
                            $totalMonthly = (float)$row['Principal'] + (float)$row['Interest_Month'];
                            ?>
                            <tr align="center">
                                <td class="fw-bold text-muted"><?php echo $i++; ?></td>
                                <td>
                                    <span class="fw-semibold">
                                        <i class="fa-regular fa-calendar me-1 text-muted"></i>
                                        <?php echo htmlspecialchars($row['Paid_Date']); ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format((float)$row['Principal'], 2); ?></td>
                                <td>$<?php echo number_format((float)$row['Interest_Month'], 2); ?></td>
                                <td>
                                    <span class="fw-bold text-primary">$<?php echo number_format($totalMonthly, 2); ?></span>
                                </td>
                                <td>
                                    <?php if ($isPaid) { ?>
                                        <span class="badge-status badge-status-paid"><?php echo __('depre_paid'); ?></span>
                                        <?php if (!empty($row['Clear_Date'])) { ?>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                <?php echo htmlspecialchars($row['Clear_Date']); ?>
                                            </small>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <span class="badge-status badge-status-pending"><?php echo __('depre_pending'); ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (!$isPaid) { ?>
                                        <a href="clearAmount.php?Depreid=<?php echo $row['Depreid']; ?>&cusid=<?php echo $row['cusid']; ?>" class="btn btn-sm btn-modern-success py-1 px-3" onclick="return confirm('<?php echo __('depre_confirm_settle'); ?>');">
                                            <i class="fa-solid fa-circle-check"></i> <?php echo __('depre_settle_btn'); ?>
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-success small fw-semibold">
                                            <i class="fa-solid fa-check-double me-1"></i> <?php echo __('depre_completed'); ?>
                                        </span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-2" style="font-size: 2rem;">
                                    <i class="fa-regular fa-calendar-xmark"></i>
                                </div>
                                <h6 class="text-muted"><?php echo __('depre_no_sched'); ?></h6>
                            </td>
                        </tr>
                        <?php
                    }
                    $conn->close();
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>