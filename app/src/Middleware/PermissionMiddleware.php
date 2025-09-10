<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Models\User;

class PermissionMiddleware
{
    public static function handle(array $requiredRoles): void
    {
        Auth::requireAuth();

        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $userRoles = User::getRoles($userId);
        $hasPermission = false;

        foreach ($requiredRoles as $role) {
            if (in_array($role, $userRoles)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            // In a real app, you might want to render a proper 403 page.
            http_response_code(403);
            echo "Forbidden: You do not have permission to access this page.";
            exit;
        }
    }
}
