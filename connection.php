<?php
declare(strict_types=1);

// -------------------------------------------------------------
// Database Credentials Resolution (Environment Variables with Local Fallback)
// -------------------------------------------------------------
$dbUrl = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL') ?: ($_ENV['MYSQL_URL'] ?? getenv('MYSQL_URL') ?: '');

if (!empty($dbUrl)) {
    $parsed = parse_url($dbUrl);
    $servername = $parsed['host'] ?? 'localhost';
    $username   = isset($parsed['user']) ? urldecode($parsed['user']) : 'root';
    $password   = isset($parsed['pass']) ? urldecode($parsed['pass']) : '';
    $dbname     = isset($parsed['path']) ? ltrim(urldecode($parsed['path']), '/') : 'db_depreciation';
    $port       = isset($parsed['port']) ? (int)$parsed['port'] : 3306;
} else {
    $servername = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: ($_ENV['DB_SERVER'] ?? getenv('DB_SERVER') ?: ($_ENV['MYSQL_HOST'] ?? getenv('MYSQL_HOST') ?: 'localhost'));
    $username   = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: ($_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: ($_ENV['MYSQL_USER'] ?? getenv('MYSQL_USER') ?: 'root'));
    $password   = $_ENV['DB_PASSWORD'] ?? (getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : ($_ENV['MYSQL_PASSWORD'] ?? (getenv('MYSQL_PASSWORD') !== false ? getenv('MYSQL_PASSWORD') : '')));
    $dbname     = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: ($_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: ($_ENV['MYSQL_DATABASE'] ?? getenv('MYSQL_DATABASE') ?: 'db_depreciation'));
    $port       = (int)($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: ($_ENV['MYSQL_PORT'] ?? getenv('MYSQL_PORT') ?: 3306));
}

// Backwards compatibility for legacy references
$severname = $servername;

// Set default timezone
date_default_timezone_set("Asia/Bangkok");

// Create MySQLi connection with optional SSL support (e.g. PlanetScale, Aiven, TiDB)
mysqli_report(MYSQLI_REPORT_OFF);

$ssl_ca = $_ENV['DB_SSL_CA'] ?? getenv('DB_SSL_CA') ?: ($_ENV['MYSQL_ATTR_SSL_CA'] ?? getenv('MYSQL_ATTR_SSL_CA') ?: null);
$ssl_enable = isset($_ENV['DB_SSL']) ? filter_var($_ENV['DB_SSL'], FILTER_VALIDATE_BOOLEAN) : (getenv('DB_SSL') !== false ? filter_var(getenv('DB_SSL'), FILTER_VALIDATE_BOOLEAN) : false);

if ($ssl_ca || $ssl_enable) {
    $conn = mysqli_init();
    if ($conn) {
        if ($ssl_ca) {
            $conn->ssl_set(null, null, $ssl_ca, null, null);
        }
        @$conn->real_connect($servername, $username, $password, $dbname, $port, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
    }
} else {
    $conn = @mysqli_connect($servername, $username, $password, $dbname, $port);
}

// Check connection
if (!$conn) {
    $errCode = mysqli_connect_errno();
    $errMsg = mysqli_connect_error();

    if ($errCode === 1049) {
        die("
            <div style='font-family: system-ui, -apple-system, sans-serif; max-width: 580px; margin: 60px auto; padding: 24px; border: 1px solid #fed7aa; background: #fffaf0; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);'>
                <h3 style='color: #c2410c; margin-top: 0; font-size: 1.25rem;'>Database Not Initialized</h3>
                <p style='color: #4b5563; line-height: 1.5;'>Database <strong>" . htmlspecialchars($dbname, ENT_QUOTES, 'UTF-8') . "</strong> was not found on this server.</p>
                <div style='margin: 20px 0;'>
                    <a href='migrate.php' style='display: inline-block; background: #4f46e5; color: #ffffff; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 500;'>Run Database Migrations Now &rarr;</a>
                </div>
                <p style='color: #6b7280; font-size: 0.85rem; margin-bottom: 0;'>Or run <code>php migrate.php</code> from your terminal.</p>
            </div>
        ");
    }

    $isVercel = isset($_ENV['VERCEL']) || getenv('VERCEL') !== false;
    if ($isVercel) {
        die("
            <div style='font-family: system-ui, -apple-system, sans-serif; max-width: 640px; margin: 60px auto; padding: 30px; border: 1px solid #e2e8f0; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);'>
                <div style='display: flex; align-items: center; gap: 12px; margin-bottom: 16px;'>
                    <div style='width: 40px; height: 40px; background: #fee2e2; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #dc2626;'>⚠️</div>
                    <h3 style='color: #0f172a; margin: 0; font-size: 1.25rem;'>Vercel MySQL Connection Required</h3>
                </div>
                <p style='color: #475569; line-height: 1.6; font-size: 0.95rem;'>
                    Vercel functions run in a serverless environment without a local MySQL server. You must connect DepreSys to an external cloud MySQL database (e.g., <strong>Aiven, TiDB Cloud, Supabase/Railway MySQL, or PlanetScale</strong>).
                </p>
                <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin: 20px 0;'>
                    <div style='font-weight: 600; font-size: 0.85rem; color: #334155; margin-bottom: 8px;'>Configure in Vercel Dashboard &rarr; Settings &rarr; Environment Variables:</div>
                    <pre style='background: #0f172a; color: #38bdf8; padding: 12px; border-radius: 6px; font-size: 0.82rem; overflow-x: auto; margin: 0;'>DB_HOST=your-cloud-mysql-host.com
DB_USER=your_db_username
DB_PASSWORD=your_db_password
DB_NAME=db_depreciation
DB_PORT=3306</pre>
                </div>
                <p style='color: #64748b; font-size: 0.85rem; margin-bottom: 0;'>
                    Database error: <code>" . htmlspecialchars((string)$errMsg, ENT_QUOTES, 'UTF-8') . " (Code: " . $errCode . ")</code>
                </p>
            </div>
        ");
    }

    die("Database connection failed: " . $errMsg);
}

// Ensure UTF-8 (utf8mb4) character set for Khmer and international Unicode
$conn->set_charset("utf8mb4");

/**
 * Null-safe HTML escaping helper for PHP 8.1+ compatibility
 */
if (!function_exists('h')) {
    function h(?string $value): string {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
