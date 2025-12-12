<?php
/**
 * Database Configuration for Cosmic Surfer 3D (MariaDB SkySQL)
 *
 * NOTE:
 * - SkySQL is protected by a firewall: you must IP-whitelist the machine where this code runs.
 * - Most SkySQL services require SSL/TLS (default ON).
 *
 * Put this file next to your /api folder and make sure all your API endpoints include it.
 */

// SkySQL connection details (provided by you)
define('DB_HOST', 'serverless-us-east1.sysp0000.db2.skysql.com');
define('DB_PORT', 4049);
define('DB_NAME', 'cosmic_surfer');      // create/select this database in SkySQL
define('DB_USER', 'dbpgf31076540');
define('DB_PASS', 'dfj7X!Q/ChpYRNW261qkZLCO');

// Optional SSL CA chain file (recommended).
// If you download the CA chain from SkySQL, put it here (example path shown).
define('DB_SSL_CA', __DIR__ . '/certs/skysql_chain.pem'); // adjust name/path if needed

function getDB(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $charset = 'utf8mb4';
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        $charset
    );

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Try to enable SSL with verification if CA file is available
    if (defined('PDO::MYSQL_ATTR_SSL_CA') && is_string(DB_SSL_CA) && file_exists(DB_SSL_CA)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = DB_SSL_CA;

        // Verify server certificate if the constant exists in this PHP build
        if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        }
    }

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Don't expose internals to clients
        error_log("Database connection failed: " . $e->getMessage());
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
        exit;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
