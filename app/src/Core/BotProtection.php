<?php
namespace App\Core;

/**
 * Bot Protection Functions
 */
class BotProtection {
    /**
     * Generate honeypot field name
     */
    public static function getHoneypotFieldName() {
        return 'website_url'; // Looks like a legitimate field to bots
    }

    /**
     * Check if honeypot was filled (indicates bot)
     */
    public static function isHoneypotFilled($formData) {
        $honeypotField = self::getHoneypotFieldName();
        return !empty($formData[$honeypotField]);
    }

    /**
     * Rate limiting - check if IP has submitted too many forms recently
     */
    public static function checkRateLimit($ip, $maxSubmissions = 3, $timeWindow = 300) { // 3 submissions per 5 minutes
        $logFile = dirname(dirname(__DIR__)) . '/database/logs/rate_limit.log';
        
        // Create logs directory if it doesn't exist
        if (!is_dir(dirname($logFile))) {
            mkdir(dirname($logFile), 0755, true);
        }
        
        $currentTime = time();
        $submissions = [];
        
        // Read existing log
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $parts = explode('|', $line);
                if (count($parts) === 2) {
                    $logTime = (int)$parts[0];
                    $logIp = $parts[1];
                    
                    // Only keep recent submissions
                    if ($currentTime - $logTime < $timeWindow) {
                        $submissions[] = ['time' => $logTime, 'ip' => $logIp];
                    }
                }
            }
        }
        
        // Count submissions from this IP
        $ipSubmissions = array_filter($submissions, function($sub) use ($ip) {
            return $sub['ip'] === $ip;
        });
        
        if (count($ipSubmissions) >= $maxSubmissions) {
            return false; // Rate limit exceeded
        }
        
        // Log this submission
        $submissions[] = ['time' => $currentTime, 'ip' => $ip];
        
        // Write back to log file
        $logData = array_map(function($sub) {
            return $sub['time'] . '|' . $sub['ip'];
        }, $submissions);
        
        file_put_contents($logFile, implode("\n", $logData) . "\n");
        
        return true; // Rate limit OK
    }

    /**
     * Check for suspicious patterns in form data
     */
    public static function detectSuspiciousContent($formData) {
        $suspiciousPatterns = [
            '/\b(viagra|cialis|casino|poker|loan|mortgage|insurance|seo|marketing)\b/i',
            '/https?:\/\/[^\s]+/i', // URLs in message
            '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i', // Email addresses in message (except email field)
        ];
        
        $textFields = ['name', 'message'];
        
        foreach ($textFields as $field) {
            if (isset($formData[$field])) {
                foreach ($suspiciousPatterns as $pattern) {
                    if (preg_match($pattern, $formData[$field])) {
                        return true; // Suspicious content detected
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Comprehensive bot protection check (without captcha)
     */
    public static function validateFormSubmission($formData, $userIP) {
        $errors = [];
        
        // Check honeypot
        if (self::isHoneypotFilled($formData)) {
            $errors[] = 'Bot detected (honeypot)';
        }
        
        // Check rate limiting
        if (!self::checkRateLimit($userIP)) {
            $errors[] = 'Too many submissions. Please wait before trying again';
        }
        
        // Check for suspicious content
        if (self::detectSuspiciousContent($formData)) {
            $errors[] = 'Suspicious content detected';
        }
        
        // Check form timing (too fast = bot)
        if (isset($formData['form_start_time'])) {
            $formTime = time() - (int)$formData['form_start_time'];
            if ($formTime < 3) { // Less than 3 seconds to fill form
                $errors[] = 'Form submitted too quickly';
            }
        }
        
        return $errors;
    }

    /**
     * Generate form start time token
     */
    public static function getFormStartTime() {
        return time();
    }
}