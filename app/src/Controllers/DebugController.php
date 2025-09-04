<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class DebugController extends Controller
{
    public function postTest(): string
    {
        ob_start();
        include __DIR__ . '/../../views/debug/post-test.php';
        return ob_get_clean();
    }


}