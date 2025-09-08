<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class ClientController extends Controller
{
    public function hub(): string
    {
        Auth::requireAuth('client');

        $data = [
            'title' => 'Client Hub',
            'page_title' => 'Client Hub',
            'username' => Auth::getUsername(),
        ];

        return $this->render('client/hub', $data, 'client');
    }
}
