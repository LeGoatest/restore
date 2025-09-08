<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class StaffController extends Controller
{
    public function hub(): string
    {
        Auth::requireAuth('staff');

        $data = [
            'title' => 'Staff Hub',
            'page_title' => 'Staff Hub',
            'username' => Auth::getUsername(),
        ];

        return $this->render('staff/hub', $data, 'staff');
    }
}
