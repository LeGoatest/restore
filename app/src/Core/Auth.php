<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private static bool $initialized = false;
    private const MAGIC_LINK_EXPIRY = 900; // 15 minutes in seconds

    public static function init(): void
    {
        if (!self::$initialized) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            self::$initialized = true;
        }
    }

    public static function generateMagicLink(string $email): ?array
    {
        self::init();
        
        $user = User::findByEmail($email);
        if (!$user) {
            return null;
        }
        
        // Generate a secure random token
        $token = bin2hex(random_bytes(32));
        $expires = time() + self::MAGIC_LINK_EXPIRY;
        
        // Store the token in the database
        User::storeMagicLinkToken($user['id'], $token, $expires);
        
        // Generate the magic link URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $magicLinkUrl = "{$protocol}://{$host}/login/verify/{$token}";
        
        return [
            'user' => $user,
            'token' => $token,
            'expires' => $expires,
            'url' => $magicLinkUrl
        ];
    }

    public static function verifyMagicLink(string $token): ?array
    {
        self::init();
        
        $user = User::findByMagicLinkToken($token);
        
        if (!$user || $user['magic_link_expires'] < time()) {
            return null;
        }
        
        // Clear the used token
        User::clearMagicLinkToken($user['id']);
        
        return $user;
    }

    public static function loginWithMagicLink(string $token): bool
    {
        $user = self::verifyMagicLink($token);
        
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