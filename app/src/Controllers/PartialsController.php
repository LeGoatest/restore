<?php

namespace App\Controllers;

use App\Core\Controller;

class PartialsController extends Controller
{
    public function junkRemoval(): string
    {
        return '<div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4">Junk Removal & Hauling</h3>
            <p class="text-gray-700 mb-4">Professional junk removal services for residential and commercial properties.</p>
            <ul class="space-y-2 text-gray-600">
                <li>• Complete home cleanouts from attic to basement</li>
                <li>• Garage and storage unit clearing</li>
                <li>• Estate sale and senior home cleanouts</li>
                <li>• Construction debris removal</li>
                <li>• Appliance and furniture removal</li>
            </ul>
            <div class="mt-6">
                <a href="/contact" class="btn-primary">Get Free Quote</a>
            </div>
        </div>';
    }

    public function surfaceCoatings(): string
    {
        return '<div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4">Surface Coatings</h3>
            <p class="text-gray-700 mb-4">Professional surface coating and restoration services.</p>
            <ul class="space-y-2 text-gray-600">
                <li>• Driveway and garage floor coatings</li>
                <li>• Concrete sealing and protection</li>
                <li>• Patio surface restoration</li>
                <li>• Protective finishes for outdoor surfaces</li>
                <li>• Commercial surface treatments</li>
            </ul>
            <div class="mt-6">
                <a href="/contact" class="btn-primary">Get Free Quote</a>
            </div>
        </div>';
    }

    public function landscaping(): string
    {
        return '<div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4">Landscaping Services</h3>
            <p class="text-gray-700 mb-4">Complete landscaping and outdoor restoration services.</p>
            <ul class="space-y-2 text-gray-600">
                <li>• Yard cleanup and debris removal</li>
                <li>• Tree and brush removal</li>
                <li>• Garden restoration and maintenance</li>
                <li>• Lawn care and maintenance</li>
                <li>• Outdoor space clearing and preparation</li>
            </ul>
            <div class="mt-6">
                <a href="/contact" class="btn-primary">Get Free Quote</a>
            </div>
        </div>';
    }

    public function cleaning(): string
    {
        return '<div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4">Exterior Cleaning</h3>
            <p class="text-gray-700 mb-4">Professional exterior cleaning and pressure washing services.</p>
            <ul class="space-y-2 text-gray-600">
                <li>• Pressure washing for driveways and walkways</li>
                <li>• House exterior cleaning and restoration</li>
                <li>• Deck and patio cleaning</li>
                <li>• Roof cleaning and maintenance</li>
                <li>• Commercial building exterior cleaning</li>
            </ul>
            <div class="mt-6">
                <a href="/contact" class="btn-primary">Get Free Quote</a>
            </div>
        </div>';
    }
}