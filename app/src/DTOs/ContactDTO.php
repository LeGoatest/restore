<?php

namespace App\DTOs;

class ContactDTO
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $email;
    public readonly ?string $phone;
    public readonly ?string $message;
    public readonly string $status;
    public readonly string $created_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'] ?? null;
        $this->message = $data['message'] ?? null;
        $this->status = $data['status'];
        $this->created_at = $data['created_at'];
    }
}
