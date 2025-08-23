<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{
    public function __construct()
    {
        session_start();
    }

    public function showLoginForm()
    {
        $this->render('login');
    }

    public function login()
    {
        $user = new User();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($user->attempt($username, $password)) {
            header('Location: /');
            exit;
        } else {
            header('Location: /login?error=1');
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    public static function hasPermission($permission)
    {
        $user = new User();
        $user = $user->find($_SESSION['user_id']);

        if (!$user) {
            return false;
        }

        $db = (new \App\Models\BaseModel())->getDb();
        $stmt = $db->prepare("
            SELECT 1
            FROM role_permissions rp
            JOIN permissions p ON rp.permission_id = p.id
            WHERE rp.role_id = ? AND p.name = ?
        ");
        $stmt->execute([$user['role_id'], $permission]);

        return $stmt->fetchColumn() !== false;
    }
}
