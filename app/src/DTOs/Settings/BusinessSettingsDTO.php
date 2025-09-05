<?php

namespace App\DTOs\Settings;

class BusinessSettingsDTO
{
    public readonly ?string $phone;
    public readonly ?string $email;
    // ... etc.

    public function __construct(array $data)
    {
        $this->phone = $data['phone'] ?? null;
        $this->email = $data['email'] ?? null;
    }
}
