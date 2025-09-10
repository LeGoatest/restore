<?php

declare(strict_types=1);

namespace App\DTOs;

class QuoteDTO
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $email;
    public readonly ?string $phone;
    public readonly ?string $address;
    public readonly ?string $service_type;
    public readonly ?string $description;
    public readonly ?string $preferred_date;
    public readonly ?string $preferred_time;
    public readonly ?float $estimated_amount;
    public readonly string $status;
    public readonly ?string $notes;
    public readonly string $created_at;
    public readonly string $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->service_type = $data['service_type'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->preferred_date = $data['preferred_date'] ?? null;
        $this->preferred_time = $data['preferred_time'] ?? null;
        $this->estimated_amount = isset($data['estimated_amount']) ? (float)$data['estimated_amount'] : null;
        $this->status = $data['status'];
        $this->notes = $data['notes'] ?? null;
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }
}
