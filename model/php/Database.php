<?php
// Database configuration OVH
define('DB_HOST', 'bilalkluzumaki.mysql.db');
define('DB_NAME', 'bilalkluzumaki');
define('DB_USER', 'bilalkluzumaki');
define('DB_PASS', 'Camelia77160'); 

// Website URL 
define('SITE_URL', 'https://cooknshare.ovh/'); 

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

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}