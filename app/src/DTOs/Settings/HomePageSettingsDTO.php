<?php

namespace App\DTOs\Settings;

class HomePageSettingsDTO
{
    public readonly string $title;
    public readonly string $meta_description;
    public readonly string $hero_title;
    public readonly string $hero_subtitle;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? 'Default Title';
        $this->meta_description = $data['meta_description'] ?? '';
        $this->hero_title = $data['hero_title'] ?? '';
        $this->hero_subtitle = $data['hero_subtitle'] ?? '';
    }
}
