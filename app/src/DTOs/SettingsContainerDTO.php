<?php

namespace App\DTOs;

use App\DTOs\Settings\HomePageSettingsDTO;
use App\DTOs\Settings\BusinessSettingsDTO;

class SettingsContainerDTO
{
    public readonly HomePageSettingsDTO $home_page;
    public readonly BusinessSettingsDTO $business;
    // ... other DTOs for other categories

    public function __construct(array $allSettings)
    {
        $this->home_page = new HomePageSettingsDTO($allSettings['home_page'] ?? []);
        $this->business = new BusinessSettingsDTO($allSettings['business'] ?? []);
        // ...
    }
}
