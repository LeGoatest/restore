<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;
use App\Models\SiteBenefit;
use App\Models\Testimonial;
use App\Models\Setting;
use App\DTOs\SettingsContainerDTO;

class HomeController extends Controller
{
    public function index(): string
    {
        // 1. Fetch all settings and populate the Settings DTO
        $allSettings = Setting::getAllSettings();
        $settingsDTO = new SettingsContainerDTO($allSettings);

        // 2. Fetch featured services, benefits, and testimonials
        $featuredServices = Service::where('is_featured', 1); // Returns ServiceDTO[]
        $benefits = SiteBenefit::all(); // Returns SiteBenefitDTO[]
        $testimonials = Testimonial::where('is_featured', 1); // Returns TestimonialDTO[]

        // 3. Pass structured data to the view
        $data = [
            'title' => $settingsDTO->home_page->title,
            'meta_description' => $settingsDTO->home_page->meta_description,
            'settings' => $settingsDTO, // Pass the whole container
            'services' => $featuredServices,
            'benefits' => $benefits,
            'testimonials' => $testimonials,
        ];

        return $this->render('public/pages/home', $data);
    }

    public function junkRemovalPartial(): string
    {
        return $this->view->render('public/partials/home/junk-removal');
    }

    public function surfaceCoatingsPartial(): string
    {
        return $this->view->render('public/partials/home/surface-coatings');
    }

    public function landscapingPartial(): string
    {
        return $this->view->render('public/partials/home/landscaping');
    }

    public function cleaningPartial(): string
    {
        return $this->view->render('public/partials/home/cleaning');
    }
}