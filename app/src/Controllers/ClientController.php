<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class ClientController extends Controller
{
    public function index(): string
    {
        Auth::requireAuth('client');

        $data = [
            'title' => 'Client Dashboard',
            'page_title' => 'Client Dashboard',
            'username' => Auth::getUsername(),
        ];

        return $this->render('client/dashboard', $data, 'client');
    }
}
