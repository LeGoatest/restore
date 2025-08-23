<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    private string $viewsPath;

    public function __construct(string $viewsPath = null)
    {
        $this->viewsPath = $viewsPath ?? __DIR__ . '/../../views';
    }

    public function render(string $view, array $data = []): string
    {
        $viewFile = $this->viewsPath . '/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }

        extract($data);
        
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }

    public function renderLayout(string $layout, string $content, array $data = []): string
    {
        $data['content'] = $content;
        return $this->render("layouts/{$layout}", $data);
    }
}