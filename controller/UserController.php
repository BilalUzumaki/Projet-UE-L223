<?php
require_once __DIR__ . '/../model/UserModel.php';

class UserController {

    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $error = '';
        $success = $_GET['success'] ?? false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!$email || !$password) {
                $error = 'Please enter both email and password';
            } else {
                $user = User::verify($email, $password);
                if ($user) {
                    $_SESSION['user'] = $user;
                    header("Location: index.php?page=recipes");
                    exit;
                } else {
                    $error = 'Invalid credentials';
                }
            }
        }

        include __DIR__ . '/../view/login.php';
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!$username || !$email || !$password) {
                $error = 'All fields are required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format';
            } elseif (User::findByEmail($email)) {
                $error = 'Email already exists';
            } else {
                User::create($username, $email, $password);
                header("Location: index.php?page=login&success=1");
                exit;
            }
        }

        include __DIR__ . '/../view/register.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}