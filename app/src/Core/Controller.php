<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Security;
use App\Core\View;

abstract class Controller
{
    protected View $view;

    protected function requireAdmin(): void
    {
        Security::requireAdmin();
    }

    protected function view(string $view, array $data = []): string
    {
        return $this->render($view, $data);
    }

    protected function partial(string $view, array $data = []): string
    {
        // Render view without layout for HTMX partials
        $sanitizedData = Security::sanitizeArray($data);
        return $this->view->render($view, $sanitizedData);
    }

    public function __construct()
    {
        Security::init();
        $this->view = new View();
        
        // Validate CSRF token for POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Security::validateCsrfToken()) {
            http_response_code(403);
            die('CSRF token validation failed');
        }
    }

    protected function render(string $view, array $data = [], string $layout = 'main'): string
    {
        // Sanitize data before rendering
        $sanitizedData = Security::sanitizeArray($data);
        $content = $this->view->render($view, $sanitizedData);
        return $this->view->renderLayout($layout, $content, $sanitizedData);
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        header('X-Content-Type-Options: nosniff');
        echo json_encode(Security::sanitizeArray($data));
        exit;
    }

    protected function redirect(string $url, int $statusCode = 302): void
    {
        // Validate URL to prevent open redirect vulnerabilities
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = '/';
        }
        http_response_code($statusCode);
        header("Location: " . $url);
        exit;
    }

    protected function validateOwnership(string $table, int $id): bool
    {
        return Security::verifyOwnership($table, $id, $_SESSION['user_id'] ?? 0);
    }
}