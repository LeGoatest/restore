<?php

namespace App\DTOs;

class SiteBenefitDTO
{
    public readonly int $id;
    public readonly string $title;
    public readonly ?string $description;
    public readonly string $icon;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->icon = $data['icon'];
    }
}
