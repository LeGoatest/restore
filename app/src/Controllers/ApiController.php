<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Analytics;

class ApiController extends Controller
{
    public function track(): string
    {
        // Set JSON content type
        header('Content-Type: application/json');
        
        // Handle both GET and POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get JSON input for POST
            $input = json_decode(file_get_contents('php://input'), true);
        } else {
            // Get query parameters for GET
            $input = [
                'page_url' => $_GET['page_url'] ?? '',
                'page_title' => $_GET['page_title'] ?? '',
                'referrer' => $_GET['referrer'] ?? ''
            ];
        }
        
        if (!$input || !isset($input['page_url']) || empty($input['page_url'])) {
            http_response_code(400);
            return json_encode(['error' => 'Invalid input']);
        }

        try {
            Analytics::track(
                $input['page_url'],
                $input['page_title'] ?? '',
                $input['referrer'] ?? ''
            );
            
            return json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            return json_encode(['error' => 'Tracking failed']);
        }
    }



    public function trackalos(): string
    {
        // Alias for track method - handles visitor tracking for admin dashboard
        return $this->track();
    }
}