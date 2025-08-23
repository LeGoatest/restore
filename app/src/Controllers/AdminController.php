<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Contact;
use App\Models\Quote;
use App\Models\Service;
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
}