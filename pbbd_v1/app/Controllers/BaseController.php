<?php

namespace App\Controllers;

class BaseController
{
    protected function render($view, $data = [])
    {
        extract($data);

        require_once __DIR__ . '/../Views/templates.php';
    }
}
