<?php
// Ensure language file is loaded
if (!function_exists('__')) {
    include_once("lang.php");
}

// Get current active script name for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);
$userName = isset($_SESSION['uName']) ? htmlspecialchars($_SESSION['uName']) : 'User';
?>

<nav class="navbar navbar-expand-lg modern-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <span class="brand-icon">
        <i class="fa-solid fa-chart-pie"></i>
      </span>
      <span>Depre<span style="color: #4f46e5;">Sys</span></span>
    </a>

    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" aria-current="page" href="index.php">
            <i class="fa-solid fa-house"></i>
            <span><?php echo __('nav_dashboard'); ?></span>
          </a>
        </li>

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

      <div class="d-flex align-items-center gap-2 gap-md-3">
        <!-- Quick Search -->
        <form class="d-none d-lg-flex position-relative" role="search" onsubmit="event.preventDefault();">
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
            <span class="lang-pill-text">EN</span>
          </a>
          <a href="<?php echo getLangUrl('kh'); ?>" 
             class="lang-pill-item <?php echo ($current_lang === 'kh') ? 'active' : ''; ?>" 
             title="ភាសាខ្មែរ" 
             aria-label="ភាសាខ្មែរ"
             aria-pressed="<?php echo ($current_lang === 'kh') ? 'true' : 'false'; ?>">
            <span class="fi fi-kh flag-icon"></span>
            <span class="lang-pill-text">KH</span>
          </a>
        </div>

        <!-- Tailwind Dark/White Mode Toggle -->
        <button type="button" 
                class="tailwind-theme-toggle" 
                id="themeToggleBtn"
                role="switch" 
                aria-checked="false" 
                title="<?php echo __('theme'); ?>" 
                aria-label="<?php echo __('theme'); ?>"
                tabindex="0">
          <input type="checkbox" id="checkbox" class="visually-hidden" aria-hidden="true">
          
          <!-- Track icons (Sun on left, Moon on right) -->
          <span class="toggle-track-icon track-sun" aria-hidden="true">
            <svg viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>
          </span>
          <span class="toggle-track-icon track-moon" aria-hidden="true">
            <svg viewBox="0 0 20 20" fill="currentColor">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
          </span>

          <!-- Sliding Thumb with Active Morphing Icon -->
          <span class="toggle-thumb" aria-hidden="true">
            <span class="thumb-icon sun-icon">
              <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
              </svg>
            </span>
            <span class="thumb-icon moon-icon">
              <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
              </svg>
            </span>
          </span>
        </button>

        <!-- User Profile Dropdown -->
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle p-1 d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: var(--radius-full); background: var(--bg-subtle);">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--gradient-primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
              <?php echo strtoupper(substr($userName, 0, 1)); ?>
            </div>
            <span class="d-none d-sm-inline fw-semibold" style="font-size: 0.88rem; padding-right: 0.5rem;"><?php echo $userName; ?></span>
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
      </div>
    </div>
  </div>
</nav>