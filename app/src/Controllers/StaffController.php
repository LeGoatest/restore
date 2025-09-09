<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class StaffController extends Controller
{
    public function index(): string
    {
        Auth::requireAuth('staff');

        $data = [
            'title' => 'Staff Dashboard',
            'page_title' => 'Staff Dashboard',
            'username' => Auth::getUsername(),
        ];

        return $this->render('staff/dashboard', $data, 'staff');
    }
}
