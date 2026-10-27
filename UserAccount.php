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
                    <i class="fa-solid fa-user-shield text-primary me-2"></i><?php echo __('user_title'); ?>
                </h3><br>
                <small class="text-muted"><?php echo __('user_subtitle'); ?></small>
            </div>
            <div>
                <a href="useraccount-add.php" class="btn btn-modern-primary">
                    <i class="fa-solid fa-user-plus"></i> <?php echo __('user_add_btn'); ?>
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr align="center">
                            <th scope="col" style="width: 70px;">#</th>
                            <th scope="col" style="width: 90px;"><?php echo __('user_th_avatar'); ?></th>
                            <th scope="col" class="text-start"><?php echo __('user_th_username'); ?></th>
                            <th scope="col"><?php echo __('user_th_userid'); ?></th>
                            <th scope="col"><?php echo __('user_th_security'); ?></th>
                            <th scope="col" style="width: 170px;"><?php echo __('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM tblusername ORDER BY userid DESC";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            $rawPhoto = (string)($row['Photo'] ?? '');
                            $photoFile = $rawPhoto !== '' ? 'images/' . h($rawPhoto) : '';
                            $hasPhoto = $rawPhoto !== '' && file_exists(__DIR__ . '/images/' . $rawPhoto);
                            $userId = (string)($row['userid'] ?? '');
                            $username = (string)($row['username'] ?? '');
                            ?>
                            <tr align="center">
                                <td class="fw-bold text-muted"><?php echo $i++; ?></td>
                                <td>
                                    <?php if ($hasPhoto) { ?>
                                        <img src="<?php echo $photoFile; ?>" class="avatar-img" alt="User Avatar">
                                    <?php } else { ?>
                                        <div class="avatar-img d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td class="text-start">
                                    <span class="fw-bold text-primary"><?php echo h($username); ?></span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2"><?php echo __('admin'); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-2 py-1">
                                        #<?php echo h($userId); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted"><i class="fa-solid fa-lock me-1 fa-xs"></i>••••••••</span>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <a href="useraccount-edit.php?param=<?php echo urlencode($userId); ?>" class="btn btn-sm btn-outline-primary" title="<?php echo __('edit'); ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="useraccount-delete.php" method="POST" class="d-inline m-0">
                                            <input type="hidden" name="userid" value="<?php echo h($userId); ?>">
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
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted mb-2" style="font-size: 2.5rem;">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                <h6 class="text-muted">No user accounts found</h6>
                                <a href="useraccount-add.php" class="btn btn-sm btn-modern-primary mt-2">
                                    <i class="fa-solid fa-user-plus"></i> <?php echo __('user_add_btn'); ?>
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

<?php
include("footer.php");
?>