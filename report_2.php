<?php 
declare(strict_types=1);
include_once("Head.php");
include_once("Navbar.php");
?>

<div class="container-fluid py-4 px-md-4">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h3 class="card-title mb-0">
                    <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i><?php echo __('rep2_title'); ?>
                </h3><br>
                <small class="text-muted"><?php echo __('rep2_subtitle'); ?></small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-modern-outline" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> <?php echo __('print_report'); ?>
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr align="center">
                            <th scope="col" style="width: 70px;">#</th>
                            <th scope="col" style="width: 80px;"><?php echo __('photo'); ?></th>
                            <th scope="col" class="text-start"><?php echo __('cust_th_name'); ?></th>
                            <th scope="col"><?php echo __('cust_th_phone'); ?></th>
                            <th scope="col" class="text-start"><?php echo __('cust_address'); ?></th>
                            <th scope="col"><?php echo __('rep2_due_date'); ?></th>
                            <th scope="col"><?php echo __('rep2_overdue_amount'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT *, CAST(Principal + interest_month AS DECIMAL(10,2)) AS Total 
                            FROM view_customer_paid
                            WHERE clear_by_userid IS NULL
                            AND Paid_Date < NOW()
                            ORDER BY Paid_Date ASC";
                    $result = $conn->query($sql);
                    $sum = 0;
                    $grandTotal = 0.0;
                    if ($result && $result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            $sum++;
                            $rowTotal = (float)($row['Total'] ?? 0);
                            $grandTotal += $rowTotal;
                            $rawPhoto = (string)($row['Photo'] ?? '');
                            $photoFile = $rawPhoto !== '' ? 'images/' . h($rawPhoto) : '';
                            $hasPhoto = $rawPhoto !== '' && file_exists(__DIR__ . '/images/' . $rawPhoto);
                            $cusId = (string)($row['cusid'] ?? '');
                            $cusName = (string)($row['cusname'] ?? '');
                            $cusTel = (string)($row['custel'] ?? '');
                            $cusAddress = (string)($row['cusAddress'] ?? '');
                            $paidDate = (string)($row['Paid_Date'] ?? '');
                            ?>
                            <tr align="center">
                                <td class="fw-bold text-muted"><?php echo $i++; ?></td>
                                <td>
                                    <?php if ($hasPhoto) { ?>
                                        <img src="<?php echo $photoFile; ?>" class="avatar-img" alt="Photo">
                                    <?php } else { ?>
                                        <div class="avatar-img d-inline-flex align-items-center justify-content-center bg-light text-primary">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td class="text-start fw-bold text-danger">
                                    <a href="depreciation.php?param=<?php echo urlencode($cusId); ?>" class="text-decoration-none text-danger">
                                        <?php echo h($cusName); ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <i class="fa-solid fa-phone me-1 text-muted"></i><?php echo h($cusTel); ?>
                                    </span>
                                </td>
                                <td class="text-start text-muted">
                                    <?php echo h($cusAddress); ?>
                                </td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i><?php echo h($paidDate); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger fs-6">$<?php echo number_format($rowTotal, 2); ?></span>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-2" style="font-size: 2.2rem;">
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                </div>
                                <h6 class="text-muted"><?php echo __('rep2_empty'); ?></h6>
                            </td>
                        </tr>
                    <?php }
                    ?>
                    </tbody>
                    <?php if ($sum > 0) { ?>
                    <tfoot class="table-light">
                        <tr align="center" class="fw-bold">
                            <td colspan="4" class="text-start ps-4"><?php echo __('rep2_total_records'); ?>: <span class="badge bg-danger"><?php echo $sum; ?></span></td>
                            <td colspan="2" class="text-end"><?php echo __('rep2_total_debt'); ?>:</td>
                            <td class="text-danger fs-6">$<?php echo number_format($grandTotal, 2); ?></td>
                        </tr>
                    </tfoot>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
include("footer.php");
?>