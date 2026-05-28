<?php
/**
 * config.php
 * ──────────────────────────────────────────────────────────────────────────────
 * Centralised database configuration with automatic environment detection.
 *
 * CREDENTIALS (never commit real passwords to GitHub)
 * ---------------------------------------------------
 * 1. Copy config.local.php.example → config.local.php
 * 2. Fill in your real DB credentials in config.local.php
 * 3. config.local.php is listed in .gitignore and must NOT be pushed to GitHub
 *
 * LOCAL: use your MariaDB credentials (phpmyadmin, etc.)
 * PRODUCTION (InfinityFree): upload config.local.php via FTP with your hosting
 *   MySQL host, user, password and database name — do not commit that file.
 *
 * If config.local.php is missing, placeholder values are used and the app
 * will fail to connect until you create the file.
 * ──────────────────────────────────────────────────────────────────────────────
 */

// ── Environment detection ─────────────────────────────────────────────────────
$_host = $_SERVER['SERVER_NAME'] ?? 'localhost';
$_isLocal = in_array($_host, ['localhost', '127.0.0.1', '::1'], true);

if ($_isLocal) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// ── Credentials ───────────────────────────────────────────────────────────────
if (file_exists(__DIR__ . '/config.local.php')) {
    require __DIR__ . '/config.local.php';
} elseif ($_isLocal) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'YOUR_LOCAL_DB_USER');
    define('DB_PASS', 'YOUR_LOCAL_DB_PASSWORD');
    define('DB_NAME', 'IoTMonitorizacion');
} else {
    define('DB_HOST', 'PROD_DB_HOST');   // e.g. sql208.infinityfree.com
    define('DB_USER', 'PROD_DB_USER');   // e.g. if0_41988251
    define('DB_PASS', 'PROD_DB_PASS');   // your InfinityFree MySQL password
    define('DB_NAME', 'PROD_DB_NAME');   // e.g. if0_41988251_iot
}

// ── Connection ────────────────────────────────────────────────────────────────
$cn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($cn->connect_errno) {
    $DB_CONNECT_ERROR = 'Error de connexió (' . $cn->connect_errno . '): '
                        . $cn->connect_error;
    $cn = null;
} else {
    $DB_CONNECT_ERROR = null;
    $cn->set_charset('utf8mb4');
}
