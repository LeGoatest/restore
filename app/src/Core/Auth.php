<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private static bool $initialized = false;

    public static function init(): void
    {
        if (!self::$initialized) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            self::$initialized = true;
        }
    }

    public static function login(string $username, string $password): bool
    {
        self::init();
        
        $user = User::authenticate($username, $password);
        
        if ($user) {
            $_SESSION['authenticated'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['login_time'] = time();
            return true;
        }

        return false;
    }

    public static function logout(): void
    {
        self::init();
        session_destroy();
        session_start();
    }

    public static function isAuthenticated(): bool
    {
        self::init();
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
    }

    public static function getUsername(): ?string
    {
        self::init();
        return $_SESSION['username'] ?? null;
    }

    public static function getUserId(): ?int
    {
        self::init();
        return $_SESSION['user_id'] ?? null;
    }

    public static function getUserRole(): ?string
    {
        self::init();
        return $_SESSION['user_role'] ?? null;
    }

    public static function getUser(): ?array
    {
        self::init();
        $userId = self::getUserId();
        
        if ($userId) {
            return User::findById($userId);
        }
        
        return null;
    }

    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }

    public static function redirectIfAuthenticated(): void
    {
        if (self::isAuthenticated()) {
            header('Location: /admin');
            exit;
        }
    }
}