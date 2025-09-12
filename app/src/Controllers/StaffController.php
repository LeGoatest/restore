<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class StaffController extends Controller
{
    public function dashboard(): string
    {
        \App\Middleware\PermissionMiddleware::handle(['Staff', 'Manager', 'Admin']);

        $data = [
            'title' => 'Staff Dashboard',
            'username' => Auth::getUsername()
        ];

        return $this->render('staff/dashboard', $data, 'main');
    }
}
