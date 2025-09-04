<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    private string $viewsPath;
    private array $escapeContexts = [];

    public function __construct(?string $viewsPath = null)
    {
        $this->viewsPath = $viewsPath ?? __DIR__ . '/../../views';
        
        // Define escape contexts for different output scenarios
        $this->escapeContexts = [
            'html' => fn($value) => htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'js' => fn($value) => json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
            'attr' => fn($value) => htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'url' => fn($value) => urlencode($value),
            'css' => fn($value) => preg_replace('/[^a-zA-Z0-9-_]/', '', $value)
        ];
    }

    public function render(string $view, array $data = []): string
    {
        $viewFile = $this->viewsPath . '/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }

        // Add escape helper functions to the view
        $e = $this->escapeContexts;
        $csrf = fn() => Security::getCsrfField();
        
        // Add security functions to data
        $data['e'] = $e;
        $data['csrf'] = $csrf;
        
        extract($data);
        
        ob_start();
        include $viewFile;
        $output = ob_get_clean();
        
        // Add security headers
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        
        return $output;
    }

    public function renderLayout(string $layout, string $content, array $data = []): string
    {
        $data['content'] = $content;
        return $this->render("layouts/{$layout}", $data);
    }
    
    public function escape(string $value, string $context = 'html'): string
    {
        if (!isset($this->escapeContexts[$context])) {
            throw new \InvalidArgumentException("Invalid escape context: {$context}");
        }
        
        return $this->escapeContexts[$context]($value);
    }
}