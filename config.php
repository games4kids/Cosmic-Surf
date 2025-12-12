<?php
/**
 * Database Configuration for Cosmic Surfer 3D
 * 
 * IMPORTANT: Update these credentials based on your MySQL setup
 * - For XAMPP: usually root with no password
 * - For production: use secure credentials
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'cosmic_surfer');
define('DB_USER', 'root');
define('DB_PASS', ''); // Change this if you have a password

// Create PDO connection
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Log error (in production, don't expose database errors to users)
        error_log("Database connection failed: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
        exit;
    }
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
