<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(): string
    {
        $data = [
            'title' => 'Contact Us - Restore Removal',
            'meta_description' => 'Get in touch with Restore Removal for your junk removal needs. Call (239) 412-1566 or fill out our contact form.',
            'contact_info' => [
                'phone' => '(239) 412-1566',
                'email' => 'info@restoreremoval.com',
                'hours' => [
                    'weekdays' => 'Mo-Fr: 7:00 AM - 6:00 PM',
                    'saturday' => 'Sa: 8:00 AM - 6:00 PM',
                    'sunday' => 'Closed',
                ],
                'location' => 'Homosassa Springs, FL',
            ],
        ];

        return $this->render('public/pages/contact', $data);
    }

    public function submit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contact');
        }

        // Basic validation
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }

        if (empty($phone)) {
            $errors[] = 'Phone number is required';
        }

        if (empty($message)) {
            $errors[] = 'Message is required';
        }

        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 400);
        }

        try {
            // Save contact to database
            $contactId = Contact::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'service_type' => $_POST['service_type'] ?? null,
                'message' => $message,
                'status' => 'new'
            ]);

            $this->json([
                'success' => true,
                'message' => 'Thank you for your message! We\'ll get back to you soon.',
                'contact_id' => $contactId
            ]);
        } catch (\Exception $e) {
            error_log('Contact form error: ' . $e->getMessage());
            $this->json([
                'success' => false,
                'message' => 'Sorry, there was an error processing your request. Please try again.'
            ], 500);
        }
    }
}