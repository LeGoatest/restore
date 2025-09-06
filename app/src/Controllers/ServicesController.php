<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;

class ServicesController extends Controller
{
    public function index(): string
    {
        $allServices = Service::all();

        $servicesByCategory = [];
        foreach ($allServices as $service) {
            $servicesByCategory[$service->category][] = $service;
        }

        $data = [
            'title' => 'Our Junk Removal Services - Restore Removal',
            'meta_description' => 'Complete junk removal services including residential cleanouts, commercial services, garage cleanouts, and more in Central Florida.',
            'servicesByCategory' => $servicesByCategory,
        ];

        return $this->render('public/pages/services', $data);
    }
}