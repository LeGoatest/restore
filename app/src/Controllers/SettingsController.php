<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Setting;
use App\Core\Security;

class SettingsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Require admin authentication
        $this->requireAdmin();
    }
    
    public function index()
    {
        $settings = [
            'general' => Setting::getByCategory('general'),
            'business' => Setting::getByCategory('business'),
            'contact' => Setting::getByCategory('contact'),
            'hours' => Setting::getByCategory('hours'),
            'seo' => Setting::getByCategory('seo'),
            'social' => Setting::getByCategory('social'),
            'analytics' => Setting::getByCategory('analytics'),
            'hero' => Setting::getByCategory('hero')
        ];
        
        return $this->view('admin/settings', [
            'settings' => $settings,
            'title' => 'Website Settings'
        ]);
    }
    
    public function save()
    {
        // Verify CSRF token
        if (!Security::validateCsrfToken()) {
            return $this->json(['error' => 'Invalid security token'], 403);
        }
        
        $category = $_POST['category'] ?? '';
        if (!in_array($category, ['general', 'business', 'contact', 'hours', 'seo', 'social', 'analytics', 'hero'])) {
            return $this->json(['error' => 'Invalid settings category'], 400);
        }
        
        // Remove category and CSRF token from data to save
        $data = $_POST;
        unset($data['category'], $data['csrf_token']);
        
        if (Setting::saveCategory($category, $data)) {
            return $this->view('admin/partials/settings-saved', [
                'message' => ucfirst($category) . ' settings saved successfully!'
            ]);
        } else {
            return $this->view('admin/partials/settings-error', [
                'message' => 'Error saving settings. Please try again.'
            ]);
        }
    }
}