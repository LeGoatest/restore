<?php

declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $mailer;
    private static ?Mailer $instance = null;

    private function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    public static function getInstance(): Mailer
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function getTemplatePath(string $templateName): string
    {
        $paths = [
            $_SERVER['DOCUMENT_ROOT'] . '/static/email-templates/' . $templateName,
            dirname(__DIR__, 2) . '/public_html/static/email-templates/' . $templateName,
            __DIR__ . '/../../public_html/static/email-templates/' . $templateName
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        throw new \Exception('Email template not found: ' . $templateName . '. Tried paths: ' . implode(', ', $paths));
    }

    private function configure(): void
    {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = getenv('SMTP_HOST') ?: 'mail.myrestorepro.com';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = getenv('SMTP_USERNAME') ?: 'info@myrestorepro.com';
            $this->mailer->Password = getenv('SMTP_PASSWORD') ?: '[*DZABa3.#&t.{mN';
            
            // Handle SMTP security
            $smtpSecure = getenv('SMTP_SECURE') ?: 'ssl';
            if ($smtpSecure === 'ssl') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            $this->mailer->Port = (int)(getenv('SMTP_PORT') ?: 465);
            
            // Enable debug for troubleshooting (remove in production)
            if (getenv('APP_DEBUG') === 'true') {
                $this->mailer->SMTPDebug = 2;
            }
            
            // Set default sender
            $this->mailer->setFrom(
                getenv('MAIL_FROM_ADDRESS') ?: 'info@myrestorepro.com',
                getenv('MAIL_FROM_NAME') ?: 'MyRestorePro'
            );
        } catch (Exception $e) {
            error_log('PHPMailer configuration error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendContactNotification(array $contact): bool
    {
        try {
            // Reset all recipients and reply-to
            $this->mailer->clearAddresses();
            $this->mailer->clearReplyTos();

            // Set recipient
            $adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@myrestorepro.com';
            $this->mailer->addAddress($adminEmail);

            // Set reply-to as the contact's email
            $this->mailer->addReplyTo($contact['email'], $contact['name']);

            // Load and prepare admin email template
            $template = file_get_contents($this->getTemplatePath('admin-notification.html'));
            
            // Email subject
            $this->mailer->Subject = 'New Contact Form Submission - Restore Removal';

            // Prepare replacements
            $replacements = [
                '{{SUBMISSION_TYPE}}' => 'Contact Form',
                '{{CUSTOMER_NAME}}' => $contact['name'],
                '{{CUSTOMER_EMAIL}}' => $contact['email'],
                '{{CUSTOMER_PHONE}}' => $contact['phone'],
                '{{SERVICE_TYPE}}' => $contact['service_type'] ?? 'General Inquiry',
                '{{SUBMISSION_DATE}}' => date('F j, Y g:i A'),
                '{{IP_ADDRESS}}' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                '{{ADMIN_URL}}' => 'https://' . $_SERVER['HTTP_HOST'] . '/admin/contacts',
            ];

            $messageSection = '<div class="detail-row">
                <span class="detail-label">Message:</span>
                <span class="detail-value">' . nl2br(htmlspecialchars($contact['message'])) . '</span>
            </div>';
            $replacements['{{MESSAGE_SECTION}}'] = $messageSection;

            // Apply all replacements
            $body = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $template
            );

            // Set email format to HTML
            $this->mailer->isHTML(true);
            $this->mailer->Body = $body;
            
            // Set plain text version
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $body));

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Contact notification email error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendQuoteRequestNotification(array $quote): bool
    {
        try {
            // Reset all recipients and reply-to
            $this->mailer->clearAddresses();
            $this->mailer->clearReplyTos();

            // Set recipient
            $adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@myrestorepro.com';
            $this->mailer->addAddress($adminEmail);

            // Set reply-to as the customer's email
            $this->mailer->addReplyTo($quote['email'], $quote['name']);

            // Load and prepare admin email template
            $template = file_get_contents($this->getTemplatePath('admin-notification.html'));

            // Email subject
            $this->mailer->Subject = 'New Quote Request - Restore Removal';

            // Prepare message section
            $messageSection = '';
            if (!empty($quote['description'])) {
                $messageSection .= '<div class="detail-row">
                    <span class="detail-label">Description:</span>
                    <span class="detail-value">' . nl2br(htmlspecialchars($quote['description'])) . '</span>
                </div>';
            }
            if (!empty($quote['address'])) {
                $messageSection .= '<div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">' . htmlspecialchars($quote['address']) . '</span>
                </div>';
            }
            if (!empty($quote['preferred_date'])) {
                $messageSection .= '<div class="detail-row">
                    <span class="detail-label">Preferred Date:</span>
                    <span class="detail-value">' . htmlspecialchars($quote['preferred_date']) . '</span>
                </div>';
            }
            if (!empty($quote['preferred_time'])) {
                $messageSection .= '<div class="detail-row">
                    <span class="detail-label">Preferred Time:</span>
                    <span class="detail-value">' . htmlspecialchars($quote['preferred_time']) . '</span>
                </div>';
            }

            // Prepare replacements
            $replacements = [
                '{{SUBMISSION_TYPE}}' => 'Quote Request',
                '{{CUSTOMER_NAME}}' => $quote['name'],
                '{{CUSTOMER_EMAIL}}' => $quote['email'],
                '{{CUSTOMER_PHONE}}' => $quote['phone'],
                '{{SERVICE_TYPE}}' => $quote['service_type'],
                '{{SUBMISSION_DATE}}' => date('F j, Y g:i A'),
                '{{IP_ADDRESS}}' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                '{{ADMIN_URL}}' => 'https://' . $_SERVER['HTTP_HOST'] . '/admin/quotes',
                '{{MESSAGE_SECTION}}' => $messageSection
            ];

            // Apply all replacements
            $body = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $template
            );

            // Set email format to HTML
            $this->mailer->isHTML(true);
            $this->mailer->Body = $body;
            
            // Set plain text version
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $body));

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Quote request notification email error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendCustomerConfirmation(array $data, string $type = 'contact'): bool
    {
        try {
            // Reset all recipients and reply-to
            $this->mailer->clearAddresses();
            $this->mailer->clearReplyTos();

            // Set recipient
            $this->mailer->addAddress($data['email'], $data['name']);

            // Load and prepare email template
            $template = file_get_contents($this->getTemplatePath('customer-receipt.html'));
            
            // Common replacements
            $replacements = [
                '{{CUSTOMER_NAME}}' => $data['name'],
                '{{CUSTOMER_EMAIL}}' => $data['email'],
                '{{CUSTOMER_PHONE}}' => $data['phone'],
                '{{SUBMISSION_DATE}}' => date('F j, Y g:i A'),
                'Text 4 Junk Removal' => 'Restore Removal',
                'textforjunkremoval.com' => 'myrestorepro.com'
            ];

            // Type-specific content
            if ($type === 'quote') {
                $this->mailer->Subject = 'Quote Request Received - Restore Removal';
                $replacements['{{SERVICE_TYPE}}'] = $data['service_type'];
                
                $messageSection = '';
                if (!empty($data['description'])) {
                    $messageSection .= '<div class="detail-row">
                        <span class="detail-label">Description:</span>
                        <span class="detail-value">' . nl2br(htmlspecialchars($data['description'])) . '</span>
                    </div>';
                }
                if (!empty($data['preferred_date'])) {
                    $messageSection .= '<div class="detail-row">
                        <span class="detail-label">Preferred Date:</span>
                        <span class="detail-value">' . htmlspecialchars($data['preferred_date']) . '</span>
                    </div>';
                }
                if (!empty($data['preferred_time'])) {
                    $messageSection .= '<div class="detail-row">
                        <span class="detail-label">Preferred Time:</span>
                        <span class="detail-value">' . htmlspecialchars($data['preferred_time']) . '</span>
                    </div>';
                }
                if (!empty($data['address'])) {
                    $messageSection .= '<div class="detail-row">
                        <span class="detail-label">Location:</span>
                        <span class="detail-value">' . htmlspecialchars($data['address']) . '</span>
                    </div>';
                }
                $replacements['{{MESSAGE_SECTION}}'] = $messageSection;
            } else {
                $this->mailer->Subject = 'Message Received - Restore Removal';
                $replacements['{{SERVICE_TYPE}}'] = $data['service_type'] ?? 'General Inquiry';
                $messageSection = '<div class="detail-row">
                    <span class="detail-label">Message:</span>
                    <span class="detail-value">' . nl2br(htmlspecialchars($data['message'])) . '</span>
                </div>';
                $replacements['{{MESSAGE_SECTION}}'] = $messageSection;
            }

            // Apply all replacements
            $body = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $template
            );

            // Set email format to HTML
            $this->mailer->isHTML(true);
            $this->mailer->Body = $body;
            
            // Set plain text version
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $body));

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Customer confirmation email error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendMagicLink(array $user, string $magicLinkUrl): bool
    {
        try {
            // Reset all recipients and reply-to
            $this->mailer->clearAddresses();
            $this->mailer->clearReplyTos();

            // Set recipient
            $this->mailer->addAddress($user['email'], $user['first_name'] . ' ' . $user['last_name']);

            // Email subject
            $this->mailer->Subject = 'Sign in to Restore Removal Admin';

            // Load and prepare email template
            $template = file_get_contents($this->getTemplatePath('magic-link.html'));
            
            // Prepare replacements
            $replacements = [
                '{{MAGIC_LINK_URL}}' => $magicLinkUrl,
                '{{FIRST_NAME}}' => $user['first_name'],
                '{{LAST_NAME}}' => $user['last_name'],
                '{{EMAIL}}' => $user['email']
            ];

            // Apply all replacements
            $body = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $template
            );

            // Set email format to HTML
            $this->mailer->isHTML(true);
            $this->mailer->Body = $body;
            
            // Set plain text version
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $body));

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Magic link email error: ' . $e->getMessage());
            return false;
        }
    }
}