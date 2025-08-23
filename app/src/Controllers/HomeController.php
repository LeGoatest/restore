<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): string
    {
        $data = [
            'title' => 'Professional Junk Removal Services in Central Florida',
            'meta_description' => 'Hassle-free junk removal in Central Florida. Professional, reliable service with free estimates. Call (239) 412-1566 today!',
            'hero' => [
                'title' => 'Professional Junk Removal Services',
                'subtitle' => 'Hassle-Free Cleanouts in Central Florida',
                'description' => 'From single items to full property cleanouts, we handle it all with professional service and environmental responsibility.',
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
                'image' => '/static/images/benefits/professional.svg',
                'title' => 'Professional Service',
                'description' => 'Licensed, insured, and reliable team',
            ],
            [
                'image' => '/static/images/benefits/eco-friendly.svg',
                'title' => 'Eco-Friendly',
                'description' => 'We donate and recycle whenever possible',
            ],
            [
                'image' => '/static/images/benefits/free-estimate.svg',
                'title' => 'Free Estimates',
                'description' => 'Transparent pricing with no hidden fees',
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