<?php
declare(strict_types=1);

$totalUser = 0;
$result = $conn->query('SELECT COUNT(*) AS total_users FROM tblusername');
if ($result && $row = $result->fetch_assoc()) {
    $totalUser = (int)($row['total_users'] ?? 0);
}

$totalCus = 0;
$resultCus = $conn->query('SELECT COUNT(*) AS total_customers FROM tblcustomer');
if ($resultCus && $rowCus = $resultCus->fetch_assoc()) {
    $totalCus = (int)($rowCus['total_customers'] ?? 0);
}

$totalPaidCount = 0;
$resPaid = $conn->query("SELECT COUNT(*) AS count FROM tbldepreciation WHERE clear_by_userid IS NOT NULL");
if ($resPaid && $r = $resPaid->fetch_assoc()) {
    $totalPaidCount = (int)($r['count'] ?? 0);
}

$uName = isset($_SESSION['uName']) ? h((string)$_SESSION['uName']) : 'Admin';
$hour = (int)date('H');
$greeting = __('good_morning');
if ($hour >= 12 && $hour < 18) {
    $greeting = __('good_afternoon');
} elseif ($hour >= 18 || $hour < 5) {
    $greeting = __('good_evening');
}
?>

<div class="container-fluid py-4 px-md-4">
    <!-- Welcome Hero Banner -->
    <div class="welcome-hero d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h1><?php echo $greeting; ?>, <?php echo $uName; ?>! 👋</h1>
            <p><?php echo __('dash_hero_desc'); ?></p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="welcome-pill">
                <i class="fa-regular fa-calendar-days"></i>
                <?php echo date("l, F j, Y"); ?>
            </span>
        </div>
    </div>

    <!-- Stat KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Active Customers -->
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-primary h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label"><?php echo __('dash_stat_active_cust'); ?></div>
                        <div class="stat-value"><?php echo $totalCus; ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span><?php echo __('dash_stat_registered'); ?></span>
                    <a href="customer.php" class="text-primary d-inline-flex align-items-center gap-1">
                        <?php echo __('dash_stat_view_list'); ?> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 2: User Registrations -->
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-accent h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label"><?php echo __('dash_stat_system_users'); ?></div>
                        <div class="stat-value"><?php echo $totalUser; ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span><?php echo __('dash_stat_auth_staff'); ?></span>
                    <a href="UserAccount.php" class="text-info d-inline-flex align-items-center gap-1">
                        <?php echo __('dash_stat_accounts'); ?> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 3: Cleared Payments -->
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-success h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label"><?php echo __('dash_stat_settled'); ?></div>
                        <div class="stat-value"><?php echo $totalPaidCount; ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span><?php echo __('dash_stat_completed'); ?></span>
                    <a href="report_4.php" class="text-success d-inline-flex align-items-center gap-1">
                        <?php echo __('dash_stat_report'); ?> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 4: Reports & Auditing -->
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-warning h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label"><?php echo __('dash_stat_pending_audit'); ?></div>
                        <div class="stat-value"><?php echo __('status'); ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span><?php echo __('dash_stat_sched_status'); ?></span>
                    <a href="report_1.php" class="text-warning d-inline-flex align-items-center gap-1">
                        <?php echo __('dash_stat_collections'); ?> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Shortcuts & Summary Action Grid -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa-solid fa-bolt text-warning"></i> <?php echo __('dash_quick_actions'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <a href="customer-add.php" class="d-block p-3 rounded-3 text-center border h-100 text-decoration-none bg-light-subtle shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;">
                                <div class="mb-2 text-primary" style="font-size: 1.8rem;">
                                    <i class="fa-solid fa-user-plus"></i>
                                </div>
                                <div class="fw-bold text-dark"><?php echo __('dash_qa_add_cust'); ?></div>
                                <small class="text-muted"><?php echo __('dash_qa_add_cust_desc'); ?></small>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a href="customer.php" class="d-block p-3 rounded-3 text-center border h-100 text-decoration-none bg-light-subtle shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;">
                                <div class="mb-2 text-success" style="font-size: 1.8rem;">
                                    <i class="fa-solid fa-table-list"></i>
                                </div>
                                <div class="fw-bold text-dark"><?php echo __('dash_qa_directory'); ?></div>
                                <small class="text-muted"><?php echo __('dash_qa_directory_desc'); ?></small>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a href="report_1.php" class="d-block p-3 rounded-3 text-center border h-100 text-decoration-none bg-light-subtle shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;">
                                <div class="mb-2 text-danger" style="font-size: 1.8rem;">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                </div>
                                <div class="fw-bold text-dark"><?php echo __('dash_qa_due'); ?></div>
                                <small class="text-muted"><?php echo __('dash_qa_due_desc'); ?></small>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a href="report_3.php" class="d-block p-3 rounded-3 text-center border h-100 text-decoration-none bg-light-subtle shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;">
                                <div class="mb-2 text-warning" style="font-size: 1.8rem;">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                </div>
                                <div class="fw-bold text-dark"><?php echo __('dash_qa_pending_loans'); ?></div>
                                <small class="text-muted"><?php echo __('dash_qa_pending_loans_desc'); ?></small>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a href="report_4.php" class="d-block p-3 rounded-3 text-center border h-100 text-decoration-none bg-light-subtle shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;">
                                <div class="mb-2 text-info" style="font-size: 1.8rem;">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="fw-bold text-dark"><?php echo __('dash_qa_paid'); ?></div>
                                <small class="text-muted"><?php echo __('dash_qa_paid_desc'); ?></small>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a href="UserAccount.php" class="d-block p-3 rounded-3 text-center border h-100 text-decoration-none bg-light-subtle shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;">
                                <div class="mb-2 text-secondary" style="font-size: 1.8rem;">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div class="fw-bold text-dark"><?php echo __('dash_qa_staff'); ?></div>
                                <small class="text-muted"><?php echo __('dash_qa_staff_desc'); ?></small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa-solid fa-circle-info text-primary"></i> <?php echo __('dash_sys_overview'); ?>
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <span class="text-muted"><?php echo __('dash_sys_status'); ?></span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                <i class="fa-solid fa-circle fa-2xs me-1"></i> <?php echo __('dash_sys_online'); ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <span class="text-muted"><?php echo __('dash_sys_db'); ?></span>
                            <span class="fw-semibold">MySQL / MariaDB</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <span class="text-muted"><?php echo __('dash_sys_ui'); ?></span>
                            <span class="fw-semibold"><?php echo ($current_lang === 'kh' ? 'រចនាប័ទ្មខ្មែរទំនើប' : 'Modern Fluid Design'); ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"><?php echo __('dash_sys_security'); ?></span>
                            <span class="fw-semibold text-primary"><?php echo __('dash_sys_sec_val'); ?></span>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mt-3" style="background: rgba(99, 102, 241, 0.08); border: 1px dashed rgba(99, 102, 241, 0.3);">
                        <div class="d-flex align-items-center gap-2 mb-1 text-primary fw-bold">
                            <i class="fa-solid fa-lightbulb"></i> <?php echo __('dash_protip_title'); ?>
                        </div>
                        <small class="text-muted">
                            <?php echo __('dash_protip_desc'); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
