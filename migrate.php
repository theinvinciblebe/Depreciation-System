<?php
declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED);

/**
 * DepreSys - Database Migration & Initialization Engine
 * Supports both CLI (php migrate.php) and Modern Web Interface.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------------------------------------
// 1. Database Configuration
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
$charset    = "utf8mb4";

$isCli = (php_sapi_name() === 'cli');
$migrationsDir = __DIR__ . '/migrations';
if (!is_dir($migrationsDir)) {
    mkdir($migrationsDir, 0755, true);
}

// -------------------------------------------------------------
// 2. Migration Core Service
// -------------------------------------------------------------
class MigrationManager {
    private PDO $pdo;
    private string $dbname;
    private string $migrationsDir;

    public function __construct(string $host, string $user, string $pass, string $dbname, string $charset, string $migrationsDir, int $port = 3306) {
        $this->dbname = $dbname;
        $this->migrationsDir = $migrationsDir;

        $multiStatementsAttr = defined('Pdo\Mysql::ATTR_MULTI_STATEMENTS')
            ? constant('Pdo\Mysql::ATTR_MULTI_STATEMENTS')
            : (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS') ? constant('PDO::MYSQL_ATTR_MULTI_STATEMENTS') : 1013);

        $pdoOptions = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            $multiStatementsAttr         => true,
        ];

        // First attempt: Connect directly to database (crucial for cloud DBs like TiDB, Aiven, Supabase where CREATE DATABASE may be restricted)
        try {
            $this->pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}", $user, $pass, $pdoOptions);
        } catch (PDOException $e) {
            // Second attempt: Connect without dbname and create database (typical for local development)
            $this->pdo = new PDO("mysql:host={$host};port={$port};charset={$charset}", $user, $pass, $pdoOptions);
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbname}` CHARACTER SET {$charset} COLLATE {$charset}_unicode_ci;");
            $this->pdo->exec("USE `{$this->dbname}`;");
        }

        // Ensure migrations tracking table exists
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `migrations` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `migration` VARCHAR(255) NOT NULL UNIQUE,
                `batch` INT NOT NULL,
                `applied_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$charset}_unicode_ci;
        ");
    }

    public function getDatabaseName(): string {
        return $this->dbname;
    }

    /**
     * Returns list of all migration files and their status
     * @return array<int, array{file: string, filename: string, applied: bool, batch: ?int, applied_at: ?string}>
     */
    public function getMigrationsList(): array {
        $stmt = $this->pdo->query("SELECT `migration`, `batch`, `applied_at` FROM `migrations` ORDER BY `id` ASC");
        $appliedRows = $stmt->fetchAll();
        $appliedMap = [];
        foreach ($appliedRows as $row) {
            $appliedMap[$row['migration']] = $row;
        }

        $files = glob($this->migrationsDir . '/*.sql');
        sort($files, SORT_NATURAL);

        $list = [];
        foreach ($files as $file) {
            $filename = basename($file);
            $isApplied = isset($appliedMap[$filename]);
            $list[] = [
                'file'       => $file,
                'filename'   => $filename,
                'applied'    => $isApplied,
                'batch'      => $isApplied ? (int)$appliedMap[$filename]['batch'] : null,
                'applied_at' => $isApplied ? (string)$appliedMap[$filename]['applied_at'] : null,
            ];
        }

        return $list;
    }

    /**
     * Run all pending migrations
     * @return array{success: bool, migrated: array<string>, error?: string}
     */
    public function runPending(): array {
        $list = $this->getMigrationsList();
        $pending = array_filter($list, fn($m) => !$m['applied']);

        if (empty($pending)) {
            return ['success' => true, 'migrated' => []];
        }

        $stmt = $this->pdo->query("SELECT MAX(`batch`) FROM `migrations`");
        $nextBatch = ((int)$stmt->fetchColumn()) + 1;

        $migrated = [];
        $recordStmt = $this->pdo->prepare("INSERT INTO `migrations` (`migration`, `batch`) VALUES (?, ?)");

        foreach ($pending as $item) {
            $sql = file_get_contents($item['file']);
            if ($sql === false || trim($sql) === '') {
                continue;
            }

            try {
                $this->pdo->exec($sql);
                // Flush multi-statement buffers
                $this->pdo->query("SELECT 1")->fetch();

                $recordStmt->execute([$item['filename'], $nextBatch]);
                $migrated[] = $item['filename'];
            } catch (Throwable $e) {
                return [
                    'success'  => false,
                    'migrated' => $migrated,
                    'error'    => "Failed migrating '{$item['filename']}': " . $e->getMessage()
                ];
            }
        }

        return ['success' => true, 'migrated' => $migrated, 'batch' => $nextBatch];
    }

    /**
     * Create a fresh new blank migration file
     */
    public function createMigrationFile(string $rawName): string {
        $slug = preg_replace('/[^a-zA-Z0-9_]+/', '_', strtolower(trim($rawName)));
        if ($slug === '') {
            $slug = 'unnamed_migration';
        }

        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_{$slug}.sql";
        $filepath = $this->migrationsDir . '/' . $filename;

        $template = "-- Migration: {$filename}\n"
                  . "-- Created At: " . date('Y-m-d H:i:s') . "\n\n"
                  . "-- Write your SQL DDL / DML below:\n\n";

        file_put_contents($filepath, $template);
        return $filename;
    }

    /**
     * Wipe and re-run all migrations (Fresh install)
     */
    public function fresh(): array {
        $this->pdo->exec("DROP DATABASE IF EXISTS `{$this->dbname}`;");
        $this->pdo->exec("CREATE DATABASE `{$this->dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $this->pdo->exec("USE `{$this->dbname}`;");
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `migrations` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `migration` VARCHAR(255) NOT NULL UNIQUE,
                `batch` INT NOT NULL,
                `applied_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        return $this->runPending();
    }

    /**
     * Re-seed sample data (users, customers, and amortization schedules)
     */
    public function seed(): array {
        $seedFiles = [
            $this->migrationsDir . '/005_seed_initial_users.sql',
            $this->migrationsDir . '/006_seed_sample_customers.sql',
        ];

        $executed = [];
        foreach ($seedFiles as $file) {
            if (file_exists($file)) {
                $sql = file_get_contents($file);
                if ($sql) {
                    $this->pdo->exec($sql);
                    $this->pdo->query("SELECT 1")->fetch();
                    $executed[] = basename($file);
                }
            }
        }
        return ['success' => true, 'seeded' => $executed];
    }
}

// -------------------------------------------------------------
// 3. CLI Mode Handler
// -------------------------------------------------------------
if ($isCli) {
    try {
        $manager = new MigrationManager($servername, $username, $password, $dbname, $charset, $migrationsDir, $port);
        $command = $argv[1] ?? 'run';

        echo "\n\033[1;34m=== DepreSys Database Migration Tool ===\033[0m\n";
        echo "Database: \033[33m{$manager->getDatabaseName()}\033[0m\n\n";

        switch ($command) {
            case 'status':
                $migrations = $manager->getMigrationsList();
                if (empty($migrations)) {
                    echo "\033[33mNo migration files found in {$migrationsDir}\033[0m\n";
                    exit(0);
                }

                printf("%-45s | %-10s | %-6s | %-20s\n", "Migration File", "Status", "Batch", "Applied At");
                echo str_repeat('-', 90) . "\n";
                foreach ($migrations as $m) {
                    $status = $m['applied'] ? "\033[32mApplied\033[0m" : "\033[33mPending\033[0m";
                    $batch = $m['batch'] !== null ? (string)$m['batch'] : '-';
                    $date = $m['applied_at'] ?? '-';
                    printf("%-45s | %-19s | %-6s | %-20s\n", $m['filename'], $status, $batch, $date);
                }
                echo "\n";
                break;

            case 'make':
                $name = $argv[2] ?? 'new_migration';
                $created = $manager->createMigrationFile($name);
                echo "\033[32m✔ Created migration file:\033[0m migrations/{$created}\n\n";
                break;

            case 'fresh':
                echo "\033[31mWARNING: This will drop database '{$manager->getDatabaseName()}' and re-run all migrations.\033[0m\n";
                if (!in_array('--force', $argv, true)) {
                    echo "Are you sure? Type 'yes' to continue: ";
                    $handle = fopen("php://stdin", "r");
                    $line = trim((string)fgets($handle));
                    if (strtolower($line) !== 'yes') {
                        echo "Aborted.\n";
                        exit(0);
                    }
                }
                echo "\nResetting database...\n";
                $result = $manager->fresh();
                if ($result['success']) {
                    echo "\033[32m✔ Database wiped and re-migrated successfully!\033[0m\n";
                    foreach ($result['migrated'] as $f) {
                        echo "  - \033[32mApplied:\033[0m {$f}\n";
                    }
                } else {
                    echo "\033[31m✖ Error: {$result['error']}\033[0m\n";
                    exit(1);
                }
                break;

            case 'seed':
                echo "Clearing and re-seeding sample data...\n";
                $result = $manager->seed();
                if ($result['success']) {
                    echo "\033[32m✔ Sample data successfully seeded!\033[0m\n";
                    foreach ($result['seeded'] as $f) {
                        echo "  - \033[32mExecuted:\033[0m {$f}\n";
                    }
                    echo "\n";
                }
                break;

            case 'run':
            default:
                echo "Running pending migrations...\n";
                $result = $manager->runPending();
                if (!$result['success']) {
                    echo "\033[31m✖ Error: {$result['error']}\033[0m\n";
                    exit(1);
                }

                if (empty($result['migrated'])) {
                    echo "\033[32m✔ Database is already up to date. Nothing to migrate.\033[0m\n\n";
                } else {
                    echo "\033[32m✔ Successfully applied " . count($result['migrated']) . " migration(s) [Batch {$result['batch']}]:\033[0m\n";
                    foreach ($result['migrated'] as $f) {
                        echo "  - \033[32mApplied:\033[0m {$f}\n";
                    }
                    echo "\n";
                }
                break;
        }
    } catch (Throwable $e) {
        echo "\033[31mDatabase Connection or Migration Error:\033[0m " . $e->getMessage() . "\n";
        exit(1);
    }
    exit(0);
}

// -------------------------------------------------------------
// 4. Web UI Handler
// -------------------------------------------------------------
$notification = null;
$error = null;

if (empty($_SESSION['migrate_csrf'])) {
    $_SESSION['migrate_csrf'] = bin2hex(random_bytes(16));
}

try {
    $manager = new MigrationManager($servername, $username, $password, $dbname, $charset, $migrationsDir, $port);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $submittedCsrf = (string)($_POST['csrf_token'] ?? '');
        if (!hash_equals($_SESSION['migrate_csrf'], $submittedCsrf)) {
            $error = "Security token expired. Please refresh the page.";
        } else {
            $action = $_POST['action'] ?? '';

            if ($action === 'run') {
                $result = $manager->runPending();
                if ($result['success']) {
                    $count = count($result['migrated']);
                    if ($count > 0) {
                        $notification = "Successfully executed {$count} migration(s) (Batch {$result['batch']})!";
                    } else {
                        $notification = "Database is already up to date! No pending migrations.";
                    }
                } else {
                    $error = $result['error'];
                }
            } elseif ($action === 'create') {
                $name = trim((string)($_POST['migration_name'] ?? ''));
                if ($name !== '') {
                    $created = $manager->createMigrationFile($name);
                    $notification = "Created new migration file: <code>migrations/{$created}</code>";
                } else {
                    $error = "Please provide a valid migration name.";
                }
            } elseif ($action === 'seed') {
                $result = $manager->seed();
                if ($result['success']) {
                    $notification = "Sample data (users, customers, and amortizations) has been cleared and re-seeded successfully!";
                } else {
                    $error = "Failed to seed sample data.";
                }
            } elseif ($action === 'fresh') {
                $result = $manager->fresh();
                if ($result['success']) {
                    $count = count($result['migrated']);
                    $notification = "Database wiped and re-initialized! Ran {$count} migration(s).";
                } else {
                    $error = $result['error'];
                }
            }
        }
    }

    $migrations = $manager->getMigrationsList();
    $totalCount = count($migrations);
    $appliedCount = count(array_filter($migrations, fn($m) => $m['applied']));
    $pendingCount = $totalCount - $appliedCount;

} catch (Throwable $e) {
    $error = "Connection / Execution Error: " . $e->getMessage();
    $migrations = [];
    $totalCount = $appliedCount = $pendingCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migrations | DepreSys</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/modern-theme.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            padding: 30px 15px;
        }
        .container {
            max-width: 980px;
        }
        .card-panel {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-bottom: 24px;
        }
        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-applied {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-pending {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .code-box {
            background: #0f172a;
            color: #38bdf8;
            padding: 12px 16px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.9rem;
            margin-top: 15px;
        }
        .btn-brand {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #fff;
            border: none;
            font-weight: 500;
            padding: 8px 18px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }
        .table > :not(caption) > * > * {
            padding: 12px 14px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1"><i class="fa fa-database text-primary me-2"></i>Database Migrations</h3>
                <p class="text-muted mb-0">Initialize schema, apply versioned updates, and seed data</p>
            </div>
            <div>
                <a href="login.php" class="btn btn-outline-secondary me-2"><i class="fa fa-sign-in me-1"></i> Login</a>
                <a href="index.php" class="btn btn-outline-primary"><i class="fa fa-dashboard me-1"></i> Dashboard</a>
            </div>
        </div>

        <!-- Alert notifications -->
        <?php if (!empty($notification)): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fa fa-check-circle fs-5 me-2"></i>
                <div><?= $notification ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fa fa-exclamation-triangle fs-5 me-2"></i>
                <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Stats Grid -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card-panel text-center py-3 mb-0">
                    <small class="text-muted text-uppercase fw-semibold">Target Database</small>
                    <div class="h5 fw-bold mt-1 text-primary mb-0"><?= htmlspecialchars($dbname, ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card-panel text-center py-3 mb-0">
                    <small class="text-muted text-uppercase fw-semibold">Total Migrations</small>
                    <div class="h5 fw-bold mt-1 mb-0"><?= $totalCount ?></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card-panel text-center py-3 mb-0">
                    <small class="text-muted text-uppercase fw-semibold">Applied</small>
                    <div class="h5 fw-bold mt-1 text-success mb-0"><?= $appliedCount ?></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card-panel text-center py-3 mb-0">
                    <small class="text-muted text-uppercase fw-semibold">Pending</small>
                    <div class="h5 fw-bold mt-1 text-warning mb-0"><?= $pendingCount ?></div>
                </div>
            </div>
        </div>

        <!-- Migration Actions & Table -->
        <div class="card-panel">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold mb-0">Migration Files</h5>
                <div class="d-flex gap-2">
                    <form method="POST" class="d-inline" onsubmit="return this.btnRun.disabled=true, true;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['migrate_csrf'] ?>">
                        <input type="hidden" name="action" value="run">
                        <button type="submit" name="btnRun" class="btn btn-brand" <?= $pendingCount === 0 ? 'disabled' : '' ?>>
                            <i class="fa fa-play me-1"></i> Run Pending (<?= $pendingCount ?>)
                        </button>
                    </form>

                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                        <i class="fa fa-plus me-1"></i> New File
                    </button>

                    <form method="POST" class="d-inline" onsubmit="return confirm('This will clean and re-seed sample users, customers, and schedules. Continue?');">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['migrate_csrf'] ?>">
                        <input type="hidden" name="action" value="seed">
                        <button type="submit" class="btn btn-outline-success" title="Reset and re-seed realistic sample data">
                            <i class="fa fa-database me-1"></i> Re-seed Data
                        </button>
                    </form>

                    <form method="POST" class="d-inline" onsubmit="return confirm('WARNING: This will DROP the database and all data, re-creating it from scratch. Continue?');">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['migrate_csrf'] ?>">
                        <input type="hidden" name="action" value="fresh">
                        <button type="submit" class="btn btn-outline-danger" title="Drop and re-run all migrations">
                            <i class="fa fa-refresh me-1"></i> Fresh Install
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 140px;">Status</th>
                            <th>Migration File</th>
                            <th style="width: 100px;">Batch</th>
                            <th style="width: 190px;">Executed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($migrations)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No migration files found in <code>migrations/</code></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($migrations as $m): ?>
                                <tr>
                                    <td>
                                        <?php if ($m['applied']): ?>
                                            <span class="stat-badge badge-applied">
                                                <i class="fa fa-check-circle"></i> Applied
                                            </span>
                                        <?php else: ?>
                                            <span class="stat-badge badge-pending">
                                                <i class="fa fa-clock-o"></i> Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <code class="fw-semibold text-dark"><?= htmlspecialchars($m['filename'], ENT_QUOTES, 'UTF-8') ?></code>
                                    </td>
                                    <td>
                                        <?= $m['batch'] !== null ? '<span class="badge bg-light text-dark border">' . $m['batch'] . '</span>' : '-' ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?= $m['applied_at'] ? htmlspecialchars($m['applied_at'], ENT_QUOTES, 'UTF-8') : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="code-box">
                <div class="text-light mb-1"><i class="fa fa-terminal text-info me-2"></i>CLI Quick Commands:</div>
                <code>php migrate.php</code> &nbsp;&mdash;&nbsp; Apply all pending migrations<br>
                <code>php migrate.php status</code> &nbsp;&mdash;&nbsp; Check migration table status<br>
                <code>php migrate.php seed</code> &nbsp;&mdash;&nbsp; Re-seed realistic sample data<br>
                <code>php migrate.php fresh</code> &nbsp;&mdash;&nbsp; Wipe database and re-migrate from scratch<br>
                <code>php migrate.php make &lt;name&gt;</code> &nbsp;&mdash;&nbsp; Generate a new migration template
            </div>
        </div>
    </div>

    <!-- Modal Create Migration -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['migrate_csrf'] ?>">
                    <input type="hidden" name="action" value="create">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalCreateLabel">Create Migration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="migration_name" class="form-label fw-semibold">Migration Name</label>
                            <input type="text" class="form-control" id="migration_name" name="migration_name" placeholder="e.g. add_phone2_to_customer" required>
                            <div class="form-text">A timestamped <code>.sql</code> file will be created in your <code>migrations/</code> directory.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brand">Create File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
