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

    /**
     * Display all quotes for the logged-in client.
     *
     * @return string The rendered quotes page.
     */
    public function quotes(): string
    {
        \App\Middleware\PermissionMiddleware::handle(['Client']);

        $userId = Auth::getUserId();

        $data = [
            'title' => 'Your Quotes',
            'page_title' => 'Your Quotes',
            'username' => Auth::getUsername(),
            'quotes' => \App\Models\Quote::getByUserId($userId),
        ];

        return $this->render('client/quotes', $data, 'client');
    }

    /**
     * Display all contact requests for the logged-in client.
     *
     * @return string The rendered contacts page.
     */
    public function contacts(): string
    {
        \App\Middleware\PermissionMiddleware::handle(['Client']);

        $userId = Auth::getUserId();

        $data = [
            'title' => 'Your Contact Requests',
            'page_title' => 'Your Contact Requests',
            'username' => Auth::getUsername(),
            'contacts' => \App\Models\Contact::getByUserId($userId),
        ];

        return $this->render('client/contacts', $data, 'client');
    }
}
