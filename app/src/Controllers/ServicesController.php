<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class ServicesController extends Controller
{
    public function index(): string
    {
        $data = [
            'title' => 'Our Junk Removal Services - Restore Removal',
            'meta_description' => 'Complete junk removal services including residential cleanouts, commercial services, garage cleanouts, and more in Central Florida.',
            'services' => $this->getAllServices(),
        ];

        return $this->render('public/pages/services', $data);
    }

    private function getAllServices(): array
    {
        return [
            'residential' => [
                'title' => 'Residential Services',
                'items' => [
                    'Home Cleanouts (Attic to Basement)',
                    'Garage Cleanouts',
                    'Basement Junk Removal',
                    'Estate Sale Cleanouts',
                    'Senior Home Cleanouts',
                    'Rummage Sale Pickup',
                ],
            ],
            'commercial' => [
                'title' => 'Commercial Services',
                'items' => [
                    'Office Cleanouts',
                    'Retail Space Clearing',
                    'Construction Debris',
                    'Property Management Services',
                ],
            ],
            'specialty' => [
                'title' => 'Specialty Removals',
                'items' => [
                    'Hot Tub Removal',
                    'Appliance Removal',
                    'Furniture Removal',
                    'Storage Unit Cleanouts',
                ],
            ],
        ];
    }
}