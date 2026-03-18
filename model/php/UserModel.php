<?php
require_once 'Database.php';

class User {
    public static function findByEmail($email) {
        $stmt = Database::getInstance()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($username, $email, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = Database::getInstance()->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed]);
    }

    public static function verify($email, $password) {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public static function updateFull($id, $username, $email, $phone, $avatar = null) {
        if ($avatar) {
            $stmt = Database::getInstance()->prepare("
                UPDATE users SET username=?, email=?, phone=?, avatar=? WHERE id=?
            ");
            return $stmt->execute([$username, $email, $phone, $avatar, $id]);
        } else {
            $stmt = Database::getInstance()->prepare("
                UPDATE users SET username=?, email=?, phone=? WHERE id=?
            ");
            return $stmt->execute([$username, $email, $phone, $id]);
        }
    }

}