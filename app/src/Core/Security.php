<?php

declare(strict_types=1);

namespace App\Core;

class Security {
    private static string $csrfToken;
    
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate CSRF token if not exists
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        self::$csrfToken = $_SESSION['csrf_token'];
    }
    
    public static function getCsrfToken(): string {
        return self::$csrfToken;
    }
    
    public static function getCsrfField(): string {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::$csrfToken) . '">';
    }
    
    public static function validateCsrfToken(): bool {
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        return $token && hash_equals(self::$csrfToken, $token);
    }
    
    public static function sanitizeOutput(string $output): string {
        return htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    public static function sanitizeArray(array $data): array {
        return array_map(function ($item) {
            if (is_array($item)) {
                return self::sanitizeArray($item);
            }
            return is_string($item) ? self::sanitizeOutput($item) : $item;
        }, $data);
    }
    
    public static function validateId(string $id, string $table, string $column = 'id'): bool {
        if (!ctype_digit($id)) {
            return false;
        }
        
        $result = Database::fetchOne(
            "SELECT 1 FROM {$table} WHERE {$column} = :id AND user_id = :user_id",
            ['id' => $id, 'user_id' => $_SESSION['user_id'] ?? 0]
        );
        
        return $result !== null;
    }
    
    public static function verifyOwnership(string $table, int $id, int $userId): bool {
        $result = Database::fetchOne(
            "SELECT 1 FROM {$table} WHERE id = :id AND user_id = :user_id",
            ['id' => $id, 'user_id' => $userId]
        );
        return $result !== null;
    }

    public static function isAdmin(): bool {
        return isset($_SESSION['user']) && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'] === true;
    }

    public static function requireAdmin(): void {
        if (!self::isAdmin()) {
            http_response_code(403);
            die('Unauthorized');
        }
    }
}