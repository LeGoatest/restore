<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Mailer;
use App\Models\Contact;
use App\Models\Quote;

class ContactController extends Controller
{
    public function index(): string
    {
        $data = [
            'title' => 'Contact Us - Restore Removal',
            'meta_description' => 'Get in touch with Restore Removal for your junk removal needs. Call (239) 412-1566 or fill out our contact form.',
            'contact_info' => [
                'phone' => '(727) 692-8167',
                'email' => 'info@myrestorepro.com',
                'hours' => [
                    'weekdays' => 'Mo-Sa: 7:00 AM - 6:00 PM',
                    'sunday' => 'Closed',
                ],
                'location' => 'Ocala, FL',
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
            $contactData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'service_type' => $_POST['service_type'] ?? null,
                'message' => $message,
                'status' => 'new'
            ];
            
            $contactId = Contact::create($contactData);
            
            // Send email notifications
            $mailer = Mailer::getInstance();
            
            // Send notification to admin
            if (!$mailer->sendContactNotification($contactData)) {
                error_log('Failed to send admin notification email for contact ID: ' . $contactId);
            }
            
            // Send confirmation to customer
            if (!$mailer->sendCustomerConfirmation($contactData, 'contact')) {
                error_log('Failed to send customer confirmation email for contact ID: ' . $contactId);
            }

            echo $this->view->render('partials/contact-success', [
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

    public function quote(): string
    {
        $data = [
            'title' => 'Get Free Quote - MyRestorePro',
            'meta_description' => 'Get a free, no-obligation quote for services in Central Florida. Fast response within 24 hours.',
        ];

        return $this->render('public/pages/quote', $data);
    }

    public function submitQuote(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/quote');
        }

        // Basic validation
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $service_type = trim($_POST['service_type'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $preferred_date = trim($_POST['preferred_date'] ?? '');
        $preferred_time = trim($_POST['preferred_time'] ?? '');

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

        if (empty($address)) {
            $errors[] = 'Address is required';
        }

        if (empty($service_type)) {
            $errors[] = 'Service type is required';
        }

        if (empty($description)) {
            $errors[] = 'Description of items is required';
        }

        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 400);
        }

        try {
            // Debug logging
            // Prepare quote data
            $quoteData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'service_type' => $service_type,
                'description' => $description,
                'preferred_date' => $preferred_date ?: null,
                'preferred_time' => $preferred_time ?: null,
                'status' => 'pending'
            ];

            error_log('Quote submission data: ' . json_encode($quoteData));

            // Save quote to database
            $quoteId = Quote::create($quoteData);

            error_log('Quote created with ID: ' . $quoteId);
            
            // Send email notifications
            $mailer = Mailer::getInstance();
            
            // Send notification to admin
            if (!$mailer->sendQuoteRequestNotification($quoteData)) {
                error_log('Failed to send admin notification email for quote ID: ' . $quoteId);
            }
            
            // Send confirmation to customer
            if (!$mailer->sendCustomerConfirmation($quoteData, 'quote')) {
                error_log('Failed to send customer confirmation email for quote ID: ' . $quoteId);
            }

            echo $this->view->render('partials/quote-success', [
                'quote_id' => $quoteId
            ]);
        } catch (\Exception $e) {
            error_log('Quote form error: ' . $e->getMessage());
            error_log('Quote form stack trace: ' . $e->getTraceAsString());
            $this->json([
                'success' => false,
                'message' => 'Sorry, there was an error processing your request. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}