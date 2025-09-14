<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class StaffController extends Controller
{
    /**
     * Display the staff dashboard with recent activity.
     *
     * @return string The rendered staff dashboard page.
     */
    public function dashboard(): string
    {
        \App\Middleware\PermissionMiddleware::handle(['Staff', 'Manager', 'Admin']);

        $data = [
            'title' => 'Staff Dashboard',
            'page_title' => 'Staff Dashboard',
            'username' => Auth::getUsername(),
            'contacts' => \App\Models\Contact::getRecent(),
            'quotes' => \App\Models\Quote::getRecent(),
        ];

        return $this->render('staff/dashboard', $data, 'admin');
    }
}
