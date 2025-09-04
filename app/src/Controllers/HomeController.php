<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): string
    {
        $data = [
            'title' => 'MyRestorePro - Professinal Restoration Services in Central Florida',
            'meta_description' => 'Hassle-free , Professional, reliable service with free estimates. Call (727) 692-8167 today!',
            'hero' => [
                'title' => 'Professional Restoration Services',
                'subtitle' => 'Hassle-Free, Reliable Professinal Restoration Services in Central Florida',
                'description' => 'From property cleanouts, To home remodels, we handle it all.',
            ],
            'services' => $this->getServices(),
            'benefits' => $this->getBenefits(),
        ];

        return $this->render('public/pages/home', $data);
    }

    private function getServices(): array
    {
        return [
            [
                'icon' => 'mdi--home',
                'title' => 'Residential Cleanouts',
                'description' => 'Complete home cleanouts from attic to basement',
            ],
            [
                'icon' => 'mdi--office-building',
                'title' => 'Commercial Services',
                'description' => 'Office cleanouts and commercial junk removal',
            ],
            [
                'icon' => 'mdi--garage',
                'title' => 'Garage Cleanouts',
                'description' => 'Reclaim your garage space with our cleanout service',
            ],
            [
                'icon' => 'mdi--storage',
                'title' => 'Storage Unit Cleanouts',
                'description' => 'Complete storage unit clearing and cleanout',
            ],
            [
                'icon' => 'mdi--hot-tub',
                'title' => 'Hot Tub Removal',
                'description' => 'Safe and professional hot tub removal service',
            ],
            [
                'icon' => 'mdi--account-group',
                'title' => 'Estate Cleanouts',
                'description' => 'Compassionate estate and senior home cleanouts',
            ],
        ];
    }

    private function getBenefits(): array
    {
        return [
            [
                'icon' => 'icon-[streamline-ultimate--delivery-truck-clock-bold]',
                'title' => 'Same-day service',
                'description' => 'Book today. We will call before we arrive',
            ],
            [
                'icon' => 'icon-[streamline-ultimate--shipping-logistic-free-shipping-delivery-truck-bold]',
                'title' => 'Get a free estimate',
                'description' => 'Transparent pricing with no hidden fees',
            ],
            [
                'icon' => 'icon-[streamline-ultimate--workflow-teamwork-user-high-five-bold]',
                'title' => 'Friendly, professional teams',
                'description' => 'Licensed, insured, and reliable team',
            ],
        ];
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