<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("connection.php");
include_once("lang.php");

if (isset($_SESSION["uid"])) {
    header("Location: index.php");
    exit();
}

$errorMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnlogin'])) {
    $txtname = trim((string)($_POST['txtUsername'] ?? ''));
    $txtpass = (string)($_POST['txtPassword'] ?? '');

    if ($txtname !== '' && $txtpass !== '') {
        $result = $conn->execute_query(
            "SELECT userid, username, userpassword FROM tblusername WHERE username = ? LIMIT 1",
            [$txtname]
        );

        if ($result && $user = $result->fetch_assoc()) {
            $storedPassword = (string)($user['userpassword'] ?? '');
            
            // Verify with password_hash or fallback to legacy plaintext
            $isPasswordValid = password_verify($txtpass, $storedPassword) || ($txtpass === $storedPassword);

            if ($isPasswordValid) {
                // Transparently upgrade legacy plaintext password to secure modern hash
                if (!password_get_info($storedPassword)['algo']) {
                    $newHash = password_hash($txtpass, PASSWORD_DEFAULT);
                    $conn->execute_query(
                        "UPDATE tblusername SET userpassword = ? WHERE userid = ?",
                        [$newHash, $user['userid']]
                    );
                }

                session_regenerate_id(true);
                $_SESSION["uid"] = (string)$user['userid'];
                $_SESSION["uName"] = (string)$user['username'];

                header('Location: index.php');
                exit();
            } else {
                $errorMessage = __('login_invalid');
            }
        } else {
            $errorMessage = __('login_invalid');
        }
    } else {
        $errorMessage = __('login_invalid');
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('login_title'); ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css">
    
    <!-- Modern Theme Stylesheet -->
    <link rel="stylesheet" href="css/modern-theme.css">

    <script>
        // Prevent dark-mode flash
        (function() {
            var theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                document.documentElement.classList.add('dark');
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('dark-mode');
                });
            }
        })();
    </script>
</head>
<body class="p-0 m-0">

    <div class="login-page-wrapper">
        <!-- Top Right Controls (Theme Toggle & Language Switcher) -->
        <div class="position-absolute top-0 end-0 p-3 p-md-4 d-flex align-items-center gap-2" style="z-index: 20;">
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
        </div>

        <!-- Ambient Glowing Animated Orbs -->
        <div class="ambient-orb orb-1"></div>
        <div class="ambient-orb orb-2"></div>
        <div class="ambient-orb orb-3"></div>

        <!-- Glassmorphic Login Card -->
        <div class="login-card">
            <!-- Brand Logo -->
            <div class="login-brand">
                <div class="login-brand-icon">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h2><?php echo __('app_name'); ?></h2>
                <p><?php echo __('login_subheading'); ?></p>
            </div>

            <!-- Error Banner -->
            <?php if (!empty($errorMessage)) { ?>
                <div class="login-error-alert animate__animated animate__shakeX">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <span><?php echo htmlspecialchars($errorMessage); ?></span>
                </div>
            <?php } ?>

            <!-- Form -->
            <form method="post" autocomplete="off">
                <div class="login-input-group">
                    <i class="fa-solid fa-user login-input-icon"></i>
                    <input type="text" name="txtUsername" class="login-input" value="admin" placeholder="<?php echo __('user_username'); ?>" required autofocus>
                </div>

                <div class="login-input-group position-relative">
                    <i class="fa-solid fa-lock login-input-icon"></i>
                    <input type="password" name="txtPassword" id="passwordInput" class="login-input" value="123" placeholder="<?php echo __('user_password'); ?>" required>
                    <button type="button" class="btn p-0 border-0 position-absolute end-0 top-50 translate-middle-y me-3 login-eye-btn" id="togglePasswordBtn" style="background: none;" aria-label="Toggle password visibility">
                        <i class="fa-regular fa-eye" id="toggleEyeIcon"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label small" for="rememberMe" style="color: var(--text-secondary); cursor: pointer;">
                            <?php echo __('login_remember'); ?>
                        </label>
                    </div>
                    <a href="#" class="small text-decoration-none login-forgot-link" onclick="alert('Please contact your administrator.'); return false;">
                        <?php echo __('login_forgot'); ?>
                    </a>
                </div>

                <button type="submit" name="btnlogin" class="login-btn">
                    <span><?php echo __('login_btn'); ?></span>
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <?php echo __('login_secure'); ?>
                    </small>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('toggleEyeIcon');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        }

        // Theme Controller
        (function () {
            var currentTheme = localStorage.getItem('theme');

            function applyTheme(isDark) {
                if (isDark) {
                    document.body.classList.add("dark-mode");
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.body.classList.remove("dark-mode");
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }

                var checkboxes = document.querySelectorAll('input#checkbox, input[type="checkbox"].theme-checkbox, .theme-switch input[type="checkbox"]');
                checkboxes.forEach(function(cb) {
                    cb.checked = isDark;
                });

                var toggles = document.querySelectorAll('.tailwind-theme-toggle, [role="switch"]#themeToggleBtn');
                toggles.forEach(function(btn) {
                    btn.setAttribute('aria-checked', isDark ? 'true' : 'false');
                });
            }

            window.toggleAppTheme = function() {
                var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark' || document.body.classList.contains('dark-mode');
                applyTheme(!isDark);
            };

            if (currentTheme === 'dark') {
                applyTheme(true);
            } else if (currentTheme === 'light') {
                applyTheme(false);
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                applyTheme(true);
            } else {
                applyTheme(false);
            }

            document.addEventListener('click', function (e) {
                var toggleBtn = e.target.closest('.tailwind-theme-toggle, #themeToggleBtn');
                if (toggleBtn) {
                    e.preventDefault();
                    window.toggleAppTheme();
                }
            });

            document.addEventListener('keydown', function (e) {
                var toggleBtn = e.target.closest('.tailwind-theme-toggle, #themeToggleBtn');
                if (toggleBtn && (e.key === ' ' || e.key === 'Enter')) {
                    e.preventDefault();
                    window.toggleAppTheme();
                }
            });

            window.addEventListener('storage', function(e) {
                if (e.key === 'theme') {
                    applyTheme(e.newValue === 'dark');
                }
            });
        })();
    </script>
</body>
</html>