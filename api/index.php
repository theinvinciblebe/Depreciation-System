<?php
declare(strict_types=1);

/**
 * Vercel Serverless Entrypoint & Front Controller
 * Handles routing for all PHP endpoints and static fallback assets.
 */

// 1. Output buffering to prevent premature header flushing during session write or redirects
ob_start();
error_reporting(E_ALL & ~E_DEPRECATED);

// 2. Set root project directory as current working directory and include path
$rootDir = realpath(__DIR__ . '/..');
if ($rootDir) {
    chdir($rootDir);
    set_include_path($rootDir . PATH_SEPARATOR . get_include_path());
}

// 3. Configure stateless signed session handler if running on Vercel or cloud serverless
// This ensures session state (user login, lang, csrf) persists across ephemeral containers!
require_once __DIR__ . '/session.php';
initializeServerlessSession();

// Ensure session is written and output buffer flushed upon exit
register_shutdown_function(function() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
});

// 4. Resolve requested URI
$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedPath = parse_url($rawUri, PHP_URL_PATH);
$path = ltrim((string)$parsedPath, '/');

// Default home request to index.php
if ($path === '' || $path === '/') {
    $path = 'index.php';
}

// Security: Prevent directory traversal and normalize path
$cleanPath = str_replace(['..', '\\'], ['', '/'], $path);
$targetFile = $rootDir . '/' . $cleanPath;

// Automatic .php extension append if file without extension is requested (e.g. /login or /customer)
if (!file_exists($targetFile) && file_exists($targetFile . '.php')) {
    $targetFile = $targetFile . '.php';
    $cleanPath .= '.php';
}

// 5. Fallback for static assets that reached PHP
if (is_file($targetFile) && !str_ends_with($targetFile, '.php')) {
    $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $mimes = [
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'json'  => 'application/json',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'webp'  => 'image/webp',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'otf'   => 'font/otf',
    ];
    if (isset($mimes[$ext])) {
        header("Content-Type: " . $mimes[$ext]);
    }
    header("Cache-Control: public, max-age=86400");
    readfile($targetFile);
    exit;
}

// 6. Execute target PHP script
if (is_file($targetFile) && str_ends_with($targetFile, '.php')) {
    // Populate script environment
    $_SERVER['SCRIPT_FILENAME'] = $targetFile;
    $_SERVER['SCRIPT_NAME'] = '/' . basename($targetFile);
    $_SERVER['PHP_SELF'] = '/' . basename($targetFile);

    require $targetFile;
    exit;
}

// 7. 404 Not Found fallback
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found - DepreSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <h1 class="display-3 fw-bold text-primary mb-2">404</h1>
        <h4 class="fw-bold text-dark mb-2">Page Not Found</h4>
        <p class="text-muted mb-4">The requested page <code><?= htmlspecialchars($path, ENT_QUOTES, 'UTF-8') ?></code> does not exist.</p>
        <a href="index.php" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold shadow-sm">
            &larr; Back to Dashboard
        </a>
    </div>
</body>
</html>
