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
                    <i class="fa-solid fa-users text-primary me-2"></i><?php echo __('cust_title'); ?>
                </h3>
                <small class="text-muted"><?php echo __('cust_subtitle'); ?></small>
            </div>
            <div>
                <a href="customer-add.php" class="btn btn-modern-primary">
                    <i class="fa-solid fa-user-plus"></i> <?php echo __('cust_add_btn'); ?>
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr align="center">
                            <th scope="col" style="width: 70px;"><?php echo __('cust_th_num'); ?></th>
                            <th scope="col" style="width: 90px;"><?php echo __('cust_th_photo'); ?></th>
                            <th scope="col" class="text-start"><?php echo __('cust_th_name'); ?></th>
                            <th scope="col"><?php echo __('cust_th_phone'); ?></th>
                            <th scope="col"><?php echo __('cust_th_product'); ?></th>
                            <th scope="col"><?php echo __('cust_th_price'); ?></th>
                            <th scope="col" style="width: 170px;"><?php echo __('cust_th_actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM tblcustomer ORDER BY cusid DESC";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            $rawPhoto = (string)($row['Photo'] ?? '');
                            $photoFile = $rawPhoto !== '' ? 'images/' . h($rawPhoto) : '';
                            $hasPhoto = $rawPhoto !== '' && file_exists(__DIR__ . '/images/' . $rawPhoto);
                            $cusId = (string)($row['cusid'] ?? '');
                            $cusName = (string)($row['cusname'] ?? '');
                            $cusTel = (string)($row['custel'] ?? '');
                            $productName = (string)($row['ProductName'] ?? '');
                            $productPrice = (float)($row['ProductPrice'] ?? 0);
                            ?>
                            <tr align="center">
                                <td class="fw-bold text-muted"><?php echo $i++; ?></td>
                                <td>
                                    <?php if ($hasPhoto) { ?>
                                        <img src="<?php echo $photoFile; ?>" class="avatar-img" alt="Customer Photo">
                                    <?php } else { ?>
                                        <div class="avatar-img d-inline-flex align-items-center justify-content-center bg-light text-primary fw-bold" style="font-size: 1.1rem;">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td class="text-start">
                                    <a href="depreciation.php?param=<?php echo urlencode($cusId); ?>" class="fw-bold text-decoration-none d-flex align-items-center gap-2" title="<?php echo __('view_schedule'); ?>">
                                        <span class="text-primary"><?php echo h($cusName); ?></span>
                                        <i class="fa-solid fa-arrow-up-right-from-square fa-xs text-muted"></i>
                                    </a>
                                    <small class="text-muted d-block">ID: #<?php echo h($cusId); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <i class="fa-solid fa-phone fa-xs text-muted me-1"></i><?php echo h($cusTel); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold"><?php echo h($productName); ?></span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">$<?php echo number_format($productPrice, 2); ?></span>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <a href="customer-edit.php?param=<?php echo urlencode($cusId); ?>" class="btn btn-sm btn-outline-primary" title="<?php echo __('edit'); ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="customer-delete.php" method="POST" class="d-inline m-0">
                                            <input type="hidden" name="cusid" value="<?php echo h($cusId); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger delete-btn" title="<?php echo __('delete'); ?>">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-2" style="font-size: 2.5rem;">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <h6 class="text-muted"><?php echo __('cust_no_records'); ?></h6>
                                <p class="text-muted small mb-3"><?php echo __('cust_start_create'); ?></p>
                                <a href="customer-add.php" class="btn btn-sm btn-modern-primary">
                                    <i class="fa-solid fa-user-plus"></i> <?php echo __('cust_add_btn'); ?>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_GET['deleted'])) { ?>
    <script>
        window.addEventListener("load", function () {
            Swal.fire({
                icon: "success",
                title: "Deleted!",
                text: "<?php echo __('cust_deleted_success'); ?>",
                confirmButtonColor: "#4f46e5"
            });
        });
    </script>
<?php } ?>

<?php
include("footer.php");
?>