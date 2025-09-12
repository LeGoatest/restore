<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class VendorController extends Controller
{
    public function dashboard(): string
    {
        \App\Middleware\PermissionMiddleware::handle(['Vendor']);

        $data = [
            'title' => 'Vendor Dashboard',
            'username' => Auth::getUsername()
        ];

        return $this->render('vendor/dashboard', $data, 'main');
    }
}
