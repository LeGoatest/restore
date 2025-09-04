<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Models\Contact;
use App\Models\Hero;
use App\Models\Quote;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Models\Analytics;

class AdminController extends Controller
{
    public function dashboard(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Admin Dashboard - Restore Removal',
            'page_title' => 'Admin Dashboard',
            'current_page' => 'dashboard',
            'username' => Auth::getUsername(),
            'contacts_count' => Contact::count(),
            'quotes_count' => Quote::count(),
            'services_count' => Service::count(),
            'recent_contacts' => Contact::getRecent(5),
            'pending_quotes' => Quote::getPending(),
            // Analytics data
            'today_visits' => Analytics::getTodayVisits(),
            'unique_visitors_today' => Analytics::getUniqueVisitorsToday(),
            'weekly_stats' => Analytics::getWeeklyStats(),
            'popular_pages' => Analytics::getPopularPages(7),
            'traffic_sources' => Analytics::getTrafficSources(7),
            'device_types' => Analytics::getDeviceTypes(7),
            'hourly_traffic' => Analytics::getHourlyTraffic(),
        ];

        return $this->render('admin/dashboard', $data, 'admin');
    }

    public function contacts(): string
    {
        Auth::requireAuth();
        
        $status = $_GET['status'] ?? 'all';
        
        if ($status === 'all') {
            $contacts = Contact::all();
        } else {
            $contacts = Contact::getByStatus($status);
        }

        $data = [
            'title' => 'Contacts - Admin Dashboard',
            'page_title' => 'Contact Management',
            'current_page' => 'contacts',
            'username' => Auth::getUsername(),
            'contacts' => $contacts,
            'current_status' => $status,
        ];

        return $this->render('admin/contacts', $data, 'admin');
    }

    public function quotes(): string
    {
        Auth::requireAuth();
        
        $status = $_GET['status'] ?? 'all';
        
        if ($status === 'all') {
            $quotes = Quote::all();
        } else {
            $quotes = Quote::getByStatus($status);
        }

        $data = [
            'title' => 'Quotes - Admin Dashboard',
            'page_title' => 'Quote Management',
            'current_page' => 'quotes',
            'username' => Auth::getUsername(),
            'quotes' => $quotes,
            'current_status' => $status,
        ];

        return $this->render('admin/quotes', $data, 'admin');
    }

    public function services(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Services - Admin Dashboard',
            'page_title' => 'Service Management',
            'current_page' => 'services',
            'username' => Auth::getUsername(),
            'services' => Service::all(),
            'categories' => Service::getCategories(),
        ];

        return $this->render('admin/services', $data, 'admin');
    }

    public function users(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Users - Admin Dashboard',
            'page_title' => 'User Management',
            'current_page' => 'users',
            'username' => Auth::getUsername(),
            'users' => User::getActive(),
        ];

        return $this->render('admin/users', $data, 'admin');
    }

    public function settings(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Settings - Admin Dashboard',
            'page_title' => 'Website Settings',
            'current_page' => 'settings',
            'username' => Auth::getUsername(),
        ];

        return $this->render('admin/settings', $data, 'admin');
    }

    public function hero(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Hero Section - Admin Dashboard',
            'page_title' => 'Hero Section',
            'current_page' => 'hero',
            'username' => Auth::getUsername(),
            'hero' => Hero::getCurrent()
        ];

        return $this->render('admin/hero', $data, 'admin');
    }
    
    /**
     * Save hero section content
     * @return string JSON response
     */
    public function saveHero()
    {
        Auth::requireAuth();
        
        if (!Security::validateCsrfToken()) {
            header('HTTP/1.1 403 Forbidden');
            return $this->view('admin/partials/settings-error', [
                'message' => 'Invalid security token'
            ]);
        }
        
        if (Hero::save($_POST['hero'])) {
            return $this->view('admin/partials/settings-saved', [
                'message' => 'Hero section saved successfully!'
            ]);
        } else {
            return $this->view('admin/partials/settings-error', [
                'message' => 'Error saving hero section. Please try again.'
            ]);
        }
    }

    /**
     * Save admin settings
     * @return mixed
     */
    public function saveSettings()
    {
        Auth::requireAuth();
        
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

        // Special handling for hero background image if present
        if ($category === 'hero' && isset($_FILES['hero']['background'])) {
            $file = $_FILES['hero']['background'];
            $uploadPath = 'public_html/static/images/hero-bg' . pathinfo($file['name'], PATHINFO_EXTENSION);
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $data['background_value'] = '/static/images/' . basename($uploadPath);
            }
        }
        
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