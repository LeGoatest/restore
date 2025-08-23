<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class AuthController extends Controller
{
    public function loginForm(): string
    {
        // Redirect if already authenticated
        Auth::redirectIfAuthenticated();

        $data = [
            'title' => 'Admin Login - Restore Removal',
            'error' => $_SESSION['login_error'] ?? null,
        ];

        // Clear error message after displaying
        unset($_SESSION['login_error']);

        return $this->render('partials/login', $data);
    }

    public function login(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $isHtmx = isset($_SERVER['HTTP_HX_REQUEST']);

        if (empty($username) || empty($password)) {
            $error = 'Username and password are required';
            
            if ($isHtmx) {
                return $this->view->render('partials/login', [
                    'title' => 'Admin Login - Restore Removal',
                    'error' => $error
                ]);
            } else {
                $_SESSION['login_error'] = $error;
                $this->redirect('/login');
            }
        }

        if (Auth::login($username, $password)) {
            if ($isHtmx) {
                // For HTMX, send a redirect header
                header('HX-Redirect: /admin');
                return '';
            } else {
                $this->redirect('/admin');
            }
        } else {
            $error = 'Invalid username or password';
            
            if ($isHtmx) {
                return $this->view->render('partials/login', [
                    'title' => 'Admin Login - Restore Removal',
                    'error' => $error
                ]);
            } else {
                $_SESSION['login_error'] = $error;
                $this->redirect('/login');
            }
        }
        
        return ''; // This should never be reached due to redirects
    }

    public function logout(): string
    {
        Auth::logout();
        $this->redirect('/login');
        return ''; // This should never be reached due to redirect
    }
}