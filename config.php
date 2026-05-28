<?php
/**
 * config.php
 * ──────────────────────────────────────────────────────────────────────────────
 * Centralised database configuration with automatic environment detection.
 *
 * HOW IT WORKS
 * ------------
 * The script inspects $_SERVER['SERVER_NAME'] (the hostname the browser used
 * to reach this page). When it matches "localhost" or "127.0.0.1" we are in
 * the local development environment; any other hostname is treated as
 * production (InfinityFree or similar shared hosting).
 *
 * RESULT
 * ------
 * After this file is included you have two variables available everywhere:
 *   $cn               — mysqli connection object, or NULL on failure
 *   $DB_CONNECT_ERROR — human-readable error string, or NULL on success
 *
 * FILES THAT USE THIS
 * -------------------
 * Every PHP file starts with:   require_once 'config.php';
 * Files that cannot work without the DB then do:
 *   if (!$cn) { die(htmlspecialchars($DB_CONNECT_ERROR)); }
 * Files that degrade gracefully (index-1.php) simply check ($cn !== null).
 *
 * FTP DEPLOYMENT TO INFINITYFREE
 * --------------------------------
 * 1. Log in to your InfinityFree control panel and note your:
 *      - MySQL host   (e.g.  sql123.epizy.com)
 *      - MySQL user   (e.g.  epiz_12345678)
 *      - MySQL pass   (set when you created the DB)
 *      - Database name (e.g. epiz_12345678_IoT)
 * 2. Fill in the four PROD_* constants below with those values.
 * 3. Open FileZilla (or any FTP client) and connect with your hosting FTP
 *    credentials (host: ftpupload.net, user/pass from your control panel).
 * 4. Upload ALL files from this folder into the remote  /htdocs/  directory.
 *    Make sure to include config.php — it must sit in the same folder as the
 *    other PHP files.
 * 5. Import IoTMonitorizacion.sql via the phpMyAdmin panel provided by
 *    InfinityFree (Databases → phpMyAdmin → Import).
 * 6. Visit  https://yourdomain.epizy.com/index-1.php  — done.
 * ──────────────────────────────────────────────────────────────────────────────
 */

// ── Environment detection ─────────────────────────────────────────────────────
// SERVER_NAME is set by the web server; when running php -S it equals the host
// you passed (localhost). We also cover the case where the script is called
// from the CLI (e.g. a cron job) by falling back to 'localhost'.
$_host = $_SERVER['SERVER_NAME'] ?? 'localhost';
$_isLocal = in_array($_host, ['localhost', '127.0.0.1', '::1'], true);

// ── Credentials ───────────────────────────────────────────────────────────────
// ── Credentials ───────────────────────────────────────────────────────────────
if ($_isLocal) {
    // ── LOCAL (php -S localhost:8080 or Apache on your machine) ───────────────
    define('DB_HOST', 'localhost');
    define('DB_USER', 'phpmyadmin');
    define('DB_PASS', '12345');
    define('DB_NAME', 'IoTMonitorizacion');
} else {
    // ── PRODUCTION (InfinityFree) — fill these in before uploading via FTP ────
    define('DB_HOST', 'sql109.infinityfree.com');
    define('DB_USER', 'if0_41988251');            // <-- CANVIAT AL TEU NÚMERO ACTUAL
    define('DB_PASS', 'Trasto21');
    define('DB_NAME', 'if0_41988251_iot');
}
// ── Connection ────────────────────────────────────────────────────────────────
// We do NOT call die() here so that pages which can degrade gracefully
// (index-1.php dashboard) still render partially instead of going blank.
// Pages that require the DB check ($cn !== null) themselves right after the
// require_once line.
$cn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($cn->connect_errno) {
    $DB_CONNECT_ERROR = 'Error de connexió (' . $cn->connect_errno . '): '
                        . $cn->connect_error;
    $cn = null;
} else {
    $DB_CONNECT_ERROR = null;
    // Enforce UTF-8 for all queries in this connection
    $cn->set_charset('utf8mb4');
}
