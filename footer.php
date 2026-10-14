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

        // Global theme controls
        window.setAppTheme = applyTheme;
        window.toggleAppTheme = function() {
            var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark' || document.body.classList.contains('dark-mode');
            applyTheme(!isDark);
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
                window.toggleAppTheme();
            }
        });

        // Keyboard navigation (Enter / Space)
        document.addEventListener('keydown', function (e) {
            var toggleBtn = e.target.closest('.tailwind-theme-toggle, .tailwind-icon-toggle, #themeToggleBtn');
            if (toggleBtn && (e.key === ' ' || e.key === 'Enter')) {
                e.preventDefault();
                window.toggleAppTheme();
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