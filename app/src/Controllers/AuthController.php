<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Mailer;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(): string
    {
        // Redirect if already authenticated
        Auth::redirectIfAuthenticated();

        $data = [
            'title' => 'Admin Login',
            'error' => $_SESSION['login_error'] ?? null,
            'success' => $_SESSION['login_success'] ?? null
        ];

        // Clear flash messages after displaying
        unset($_SESSION['login_error'], $_SESSION['login_success']);

        return $this->render('partials/login', $data, 'main');
    }

    public function requestLogin(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return '';
        }

        $email = trim($_POST['email'] ?? '');
        $isHtmx = isset($_SERVER['HTTP_HX_REQUEST']);
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address';
            
            if ($isHtmx) {
                return $this->view->render('partials/login', [
                    'title' => 'Admin Login - Restore Removal',
                    'error' => $error
                ]);
            } else {
                $_SESSION['login_error'] = $error;
                $this->redirect('/login');
                return '';
            }
        }

        try {
            $magicLink = Auth::generateMagicLink($email);
            
            if ($magicLink) {
                // Send magic link email
                $mailer = Mailer::getInstance();
                $emailSent = $mailer->sendMagicLink($magicLink['user'], $magicLink['url']);
                
                if (!$emailSent) {
                    // Log the error but don't reveal it to the user
                    error_log('Failed to send magic link email for user: ' . $email);
                }
            }

            // Don't reveal whether the email exists or if email sending failed
            $success = 'If your email is registered, you will receive a login link shortly.';
            
            if ($isHtmx) {
                return $this->view->render('partials/login', [
                    'title' => 'Admin Login - Restore Removal',
                    'success' => $success
                ]);
            } else {
                $_SESSION['login_success'] = $success;
                $this->redirect('/login');
                return '';
            }

        } catch (\Exception $e) {
            error_log('Magic link request error: ' . $e->getMessage());
            
            // Still show success message to prevent email enumeration
            $success = 'If your email is registered, you will receive a login link shortly.';
            
            if ($isHtmx) {
                return $this->view->render('partials/login', [
                    'title' => 'Admin Login - Restore Removal',
                    'success' => $success
                ]);
            } else {
                $_SESSION['login_success'] = $success;
                $this->redirect('/login');
                return '';
            }
        }
    }

    public function verifyMagicLink(string $token): string
    {
        try {
            if (Auth::loginWithMagicLink($token)) {
                $role = Auth::getUserRole();
                switch ($role) {
                    case 'admin':
                        $this->redirect('/adminhub');
                        break;
                    case 'staff':
                        $this->redirect('/staffhub');
                        break;
                    case 'client':
                        $this->redirect('/clienthub');
                        break;
                    default:
                        $this->redirect('/');
                        break;
                }
            } else {
                $_SESSION['login_error'] = 'This login link has expired or is invalid. Please request a new one.';
                $this->redirect('/login');
            }
        } catch (\Exception $e) {
            error_log('Magic link verification error: ' . $e->getMessage());
            $_SESSION['login_error'] = 'This login link has expired or is invalid. Please request a new one.';
            $this->redirect('/login');
        }
        return ''; // This should never be reached due to redirect
    }

    public function logout(): string
    {
        Auth::logout();
        $this->redirect('/login');
        return ''; // This should never be reached due to redirect
    }
}