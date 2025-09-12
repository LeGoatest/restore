<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class ClientController extends Controller
{
    public function dashboard(): string
    {
        \App\Middleware\PermissionMiddleware::handle(['Client']);

        $userId = Auth::getUserId();

        $data = [
            'title' => 'Client Dashboard',
            'page_title' => 'Client Dashboard',
            'username' => Auth::getUsername(),
            'contacts' => \App\Models\Contact::getByUserId($userId),
            'quotes' => \App\Models\Quote::getByUserId($userId),
        ];

        return $this->render('client/dashboard', $data, 'client');
    }
}
