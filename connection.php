<?php
declare(strict_types=1);

$servername = "localhost";
$severname = $servername; // Backwards compatibility for legacy references
$username = "root";
$password = "";
$dbname = "db_depreciation";

// Set default timezone
date_default_timezone_set("Asia/Bangkok");

// Create MySQLi connection
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    if (mysqli_connect_errno() === 1049) {
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
    die("Database connection failed: " . mysqli_connect_error());
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
