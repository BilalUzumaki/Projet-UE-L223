<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'cooknshare');
define('DB_USER', 'root');
define('DB_PASS', '');

// Website URL (match development server)
define('SITE_URL', 'http://localhost:223'); 

// Database singleton compatible avec Database::getInstance()

class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Retourne l’instance PDO
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}