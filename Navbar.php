<?php
declare(strict_types=1);

// Ensure language file is loaded
if (!function_exists('__')) {
    include_once("lang.php");
}

// Get current active script name for active link highlighting
$current_page = basename($_SERVER['PHP_SELF'] ?? '');
$rawUserName = isset($_SESSION['uName']) ? (string)$_SESSION['uName'] : 'User';
$userName = htmlspecialchars($rawUserName, ENT_QUOTES, 'UTF-8');
$userInitial = strtoupper(substr($rawUserName !== '' ? $rawUserName : 'U', 0, 1));
?>

<nav class="navbar navbar-expand-lg modern-navbar">
  <div class="container-fluid px-2 px-sm-3 px-lg-4">
    <!-- Brand Logo & Name -->
    <a class="navbar-brand" href="index.php">
      <span class="brand-icon">
        <i class="fa-solid fa-chart-pie"></i>
      </span>
      <span class="brand-text">Depre<span style="color: #4f46e5;">Sys</span></span>
    </a>

    <!-- Top Right Quick Controls (Theme, Language, User Dropdown, Mobile Toggler) -->
    <div class="d-flex align-items-center gap-1 gap-sm-2 gap-md-3 order-lg-3 ms-auto ms-lg-0">
      <!-- Quick Search Bar (Large Desktops) -->
      <form class="d-none d-xl-flex position-relative me-1" role="search" onsubmit="event.preventDefault();">
        <input class="form-control modern-search-input" type="search" placeholder="<?php echo __('search_placeholder'); ?>" aria-label="Search">
      </form>

      <!-- Language Switcher Pill (EN / KH) -->
      <div class="lang-switch-pill" role="group" aria-label="<?php echo __('language'); ?>">
        <a href="<?php echo getLangUrl('en'); ?>" 
           class="lang-pill-item <?php echo ($current_lang === 'en') ? 'active' : ''; ?>" 
           title="English" 
           aria-label="English"
           aria-pressed="<?php echo ($current_lang === 'en') ? 'true' : 'false'; ?>">
          <span class="fi fi-gb flag-icon"></span>
          <span class="lang-pill-text d-none d-xs-inline">EN</span>
        </a>
        <a href="<?php echo getLangUrl('kh'); ?>" 
           class="lang-pill-item <?php echo ($current_lang === 'kh') ? 'active' : ''; ?>" 
           title="ភាសាខ្មែរ" 
           aria-label="ភាសាខ្មែរ"
           aria-pressed="<?php echo ($current_lang === 'kh') ? 'true' : 'false'; ?>">
          <span class="fi fi-kh flag-icon"></span>
          <span class="lang-pill-text d-none d-xs-inline">KH</span>
        </a>
      </div>

      <!-- Single Theme Toggle Button -->
      <button type="button" 
              class="tailwind-theme-toggle focus-ring" 
              id="themeToggleBtn"
              title="<?php echo __('theme'); ?>" 
              aria-label="<?php echo __('theme'); ?>"
              tabindex="0">
        <span class="theme-toggle-icon-wrap" aria-hidden="true">
          <!-- Sun Icon (Active in Light Mode) -->
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide-icon lucide-sun" aria-hidden="true">
            <circle cx="12" cy="12" r="4"></circle>
            <path d="M12 2v2"></path>
            <path d="M12 20v2"></path>
            <path d="m4.93 4.93 1.41 1.41"></path>
            <path d="m17.66 17.66 1.41 1.41"></path>
            <path d="M2 12h2"></path>
            <path d="M20 12h2"></path>
            <path d="m6.34 17.66-1.41 1.41"></path>
            <path d="m19.07 4.93-1.41 1.41"></path>
          </svg>
          <!-- Moon Icon (Active in Dark Mode) -->
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide-icon lucide-moon" aria-hidden="true">
            <path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"></path>
          </svg>
        </span>
      </button>

      <!-- User Profile Dropdown (Compact on Mobile, Full on Desktop) -->
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle p-1 d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: var(--radius-full); background: var(--bg-subtle);">
          <div class="user-avatar-badge">
            <?php echo $userInitial; ?>
          </div>
          <span class="d-none d-md-inline fw-semibold" style="font-size: 0.88rem; padding-right: 0.5rem;"><?php echo $userName; ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
          <li class="px-3 py-2">
            <div class="fw-bold text-truncate" style="max-width: 180px;"><?php echo $userName; ?></div>
            <small class="text-muted"><?php echo __('admin'); ?></small>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item" href="UserAccount.php">
              <i class="fa-solid fa-user-gear"></i>
              <span><?php echo __('account_settings'); ?></span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="logout.php" method="POST" class="m-0">
              <button type="submit" class="dropdown-item text-danger logout-btn">
                <i class="fa-solid fa-right-from-bracket text-danger"></i>
                <span><?php echo __('sign_out'); ?></span>
              </button>
            </form>
          </li>
        </ul>
      </div>

      <!-- Modern Animated Navbar Toggler (Visible on Mobile/Tablet < lg) -->
      <button class="navbar-toggler modern-nav-toggler border-0 shadow-none d-lg-none" 
              type="button" 
              data-bs-toggle="collapse" 
              data-bs-target="#navbarSupportedContent" 
              aria-controls="navbarSupportedContent" 
              aria-expanded="false" 
              aria-label="Toggle navigation">
        <span class="toggler-icon-bar"></span>
        <span class="toggler-icon-bar"></span>
        <span class="toggler-icon-bar"></span>
      </button>
    </div>

    <!-- Responsive Collapsible Content Drawer -->
    <div class="collapse navbar-collapse order-lg-2" id="navbarSupportedContent">
      <!-- Search Bar in Mobile/Tablet Drawer (< xl) -->
      <form class="d-flex d-xl-none my-2 my-lg-0 me-lg-2 position-relative w-100" role="search" onsubmit="event.preventDefault();">
        <input class="form-control modern-search-input w-100" type="search" placeholder="<?php echo __('search_placeholder'); ?>" aria-label="Search">
      </form>

      <!-- Main Navigation Menu -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
        <!-- Dashboard Link -->
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" aria-current="page" href="index.php">
            <i class="fa-solid fa-house"></i>
            <span><?php echo __('nav_dashboard'); ?></span>
          </a>
        </li>

        <!-- Management Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?php echo in_array($current_page, ['customer.php', 'customer-add.php', 'customer-edit.php', 'UserAccount.php', 'useraccount-add.php', 'useraccount-edit.php']) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-sliders"></i>
            <span><?php echo __('nav_management'); ?></span>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="customer.php">
                <i class="fa-solid fa-users"></i>
                <span><?php echo __('nav_customer_list'); ?></span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="customer-add.php">
                <i class="fa-solid fa-user-plus"></i>
                <span><?php echo __('nav_add_customer'); ?></span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="UserAccount.php">
                <i class="fa-solid fa-user-gear"></i>
                <span><?php echo __('nav_user_accounts'); ?></span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Reports Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?php echo (strpos($current_page, 'report') !== false) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-chart-line"></i>
            <span><?php echo __('nav_reports'); ?></span>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="report_1.php">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <span><?php echo __('nav_rep_due'); ?></span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="report_2.php">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span><?php echo __('nav_rep_overdue'); ?></span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="report_3.php">
                <i class="fa-solid fa-hourglass-half"></i>
                <span><?php echo __('nav_rep_pending'); ?></span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="report_4.php">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo __('nav_rep_paid'); ?></span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="report_customer.php">
                <i class="fa-solid fa-file-lines"></i>
                <span><?php echo __('nav_rep_all_cust'); ?></span>
              </a>
            </li>
          </ul>
        </li>
      </ul>

      <!-- Mobile Drawer User Quick Links (Visible on Mobile/Tablet Drawer < lg) -->
      <div class="d-lg-none mobile-drawer-user border-top pt-3 mt-2">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center gap-2">
            <div class="user-avatar-badge" style="width: 36px; height: 36px; font-size: 0.9rem;">
              <?php echo $userInitial; ?>
            </div>
            <div>
              <div class="fw-bold small text-truncate" style="max-width: 160px;"><?php echo $userName; ?></div>
              <small class="text-muted d-block" style="font-size: 0.75rem;"><?php echo __('admin'); ?></small>
            </div>
          </div>
          <form action="logout.php" method="POST" class="m-0">
            <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 py-1 px-2" style="font-size: 0.78rem;">
              <i class="fa-solid fa-right-from-bracket"></i>
              <span><?php echo __('sign_out'); ?></span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>