<?php

namespace App\DTOs;

class ServiceDTO
{
    public readonly int $id;
    public readonly string $name;
    public readonly ?string $description;
    public readonly ?string $icon;
    public readonly ?string $category;
    public readonly bool $is_featured;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'] ?? null;
        $this->icon = $data['icon'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->is_featured = (bool)($data['is_featured'] ?? false);
    }
}
