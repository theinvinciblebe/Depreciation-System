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
