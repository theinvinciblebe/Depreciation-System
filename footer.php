<?php
// Ensure language file is loaded
if (!function_exists('__')) {
    include_once("lang.php");
}
?>

<!-- Modern Responsive Sticky Footer -->
<footer class="modern-footer mt-auto">
    <div class="container-fluid py-3 px-3 px-md-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="footer-brand d-flex align-items-center gap-2">
                <span class="brand-icon footer-mini-icon">
                    <i class="fa-solid fa-chart-pie"></i>
                </span>
                <span class="fw-bold" style="font-size: 0.95rem;">Depre<span style="color: #4f46e5;">Sys</span></span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 ms-1" style="font-size: 0.72rem;">v2.0</span>
                <span class="text-muted d-none d-sm-inline ms-2 small">&copy; <?php echo date('Y'); ?> <?php echo __('app_subtitle'); ?></span>
            </div>
            <div class="footer-links d-flex flex-wrap justify-content-center align-items-center gap-3 small">
                <a href="index.php" class="text-secondary text-decoration-none hover-primary">
                    <i class="fa-solid fa-house me-1"></i><?php echo __('nav_dashboard'); ?>
                </a>
                <span class="text-muted opacity-50">&bull;</span>
                <a href="customer.php" class="text-secondary text-decoration-none hover-primary">
                    <i class="fa-solid fa-users me-1"></i><?php echo __('nav_customer_list'); ?>
                </a>
                <span class="text-muted opacity-50">&bull;</span>
                <a href="report_1.php" class="text-secondary text-decoration-none hover-primary">
                    <i class="fa-solid fa-chart-line me-1"></i><?php echo __('nav_reports'); ?>
                </a>
                <span class="text-muted opacity-50">&bull;</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                    <i class="fa-solid fa-circle fa-2xs me-1"></i><?php echo __('dash_sys_online'); ?>
                </span>
            </div>
        </div>
        <div class="text-center d-sm-none text-muted small mt-2">
            &copy; <?php echo date('Y'); ?> <?php echo __('app_subtitle'); ?>
        </div>
    </div>
</footer>

<!-- Footer Scripts & Theme Controller -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/sweetalert.js"></script>

<script>
    (function () {
        var currentTheme = localStorage.getItem('theme');
        var mainHeader = document.querySelector('.main-header');

        function applyTheme(isDark) {
            if (isDark) {
                document.body.classList.add("dark-mode");
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                document.documentElement.classList.add('dark');
                if (mainHeader) {
                    mainHeader.classList.add('navbar-dark');
                    mainHeader.classList.remove('navbar-light');
                }
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove("dark-mode");
                document.documentElement.setAttribute('data-bs-theme', 'light');
                document.documentElement.classList.remove('dark');
                if (mainHeader) {
                    mainHeader.classList.add('navbar-light');
                    mainHeader.classList.remove('navbar-dark');
                }
                localStorage.setItem('theme', 'light');
            }

            // Sync all checkboxes & toggle buttons on page
            var checkboxes = document.querySelectorAll('input#checkbox, input[type="checkbox"].theme-checkbox, .theme-switch input[type="checkbox"]');
            checkboxes.forEach(function(cb) {
                cb.checked = isDark;
            });

            var toggles = document.querySelectorAll('.tailwind-theme-toggle, [role="switch"]#themeToggleBtn');
            toggles.forEach(function(btn) {
                btn.setAttribute('aria-checked', isDark ? 'true' : 'false');
            });

            // Dispatch global event
            window.dispatchEvent(new CustomEvent('appThemeChanged', { detail: { isDark: isDark, theme: isDark ? 'dark' : 'light' } }));
        }

        // Global theme controls with View Transitions API (Circular Ripple effect)
        window.setAppTheme = applyTheme;
        window.toggleAppTheme = function(event) {
            var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark' || document.body.classList.contains('dark-mode');
            var nextIsDark = !isDark;

            if (!document.startViewTransition || (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) {
                applyTheme(nextIsDark);
                return;
            }

            var x, y;
            if (event && (typeof event.clientX === 'number' && typeof event.clientY === 'number' && (event.clientX !== 0 || event.clientY !== 0))) {
                x = event.clientX;
                y = event.clientY;
            } else {
                var btn = document.querySelector('#themeToggleBtn, .tailwind-theme-toggle');
                if (btn) {
                    var rect = btn.getBoundingClientRect();
                    x = rect.left + rect.width / 2;
                    y = rect.top + rect.height / 2;
                } else {
                    x = window.innerWidth / 2;
                    y = window.innerHeight / 2;
                }
            }

            var endRadius = Math.hypot(
                Math.max(x, window.innerWidth - x),
                Math.max(y, window.innerHeight - y)
            );

            document.documentElement.style.setProperty('--theme-cx', x + 'px');
            document.documentElement.style.setProperty('--theme-cy', y + 'px');
            document.documentElement.style.setProperty('--theme-r', endRadius + 'px');
            document.documentElement.setAttribute('data-theme-anim', '1');

            var transition = document.startViewTransition(function() {
                applyTheme(nextIsDark);
            });

            transition.finished.finally(function() {
                document.documentElement.removeAttribute('data-theme-anim');
            });
        };

        // Initialize state on page load
        if (currentTheme === 'dark') {
            applyTheme(true);
        } else if (currentTheme === 'light') {
            applyTheme(false);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            applyTheme(true);
        } else {
            applyTheme(false);
        }

        // Click listener for Tailwind toggle buttons
        document.addEventListener('click', function (e) {
            var toggleBtn = e.target.closest('.tailwind-theme-toggle, .tailwind-icon-toggle, #themeToggleBtn');
            if (toggleBtn) {
                e.preventDefault();
                window.toggleAppTheme(e);
            }
        });

        // Keyboard navigation (Enter / Space)
        document.addEventListener('keydown', function (e) {
            var toggleBtn = e.target.closest('.tailwind-theme-toggle, .tailwind-icon-toggle, #themeToggleBtn');
            if (toggleBtn && (e.key === ' ' || e.key === 'Enter')) {
                e.preventDefault();
                window.toggleAppTheme(e);
            }
        });

        // Multi-tab synchronization
        window.addEventListener('storage', function(e) {
            if (e.key === 'theme') {
                applyTheme(e.newValue === 'dark');
            }
        });

        // Legacy checkbox change support
        var legacySwitches = document.querySelectorAll('.theme-switch input[type="checkbox"]');
        legacySwitches.forEach(function(sw) {
            sw.addEventListener('change', function (e) {
                applyTheme(e.target.checked);
            });
        });
    })();
</script>

</body>
</html>