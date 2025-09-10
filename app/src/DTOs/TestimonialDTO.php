<?php

namespace App\DTOs;

class TestimonialDTO
{
    public readonly int $id;
    public readonly string $author_name;
    public readonly ?string $author_location;
    public readonly string $quote_text;
    public readonly ?int $rating;
    public readonly string $created_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->author_name = $data['author_name'];
        $this->author_location = $data['author_location'] ?? null;
        $this->quote_text = $data['quote_text'];
        $this->rating = isset($data['rating']) ? (int)$data['rating'] : null;
        $this->created_at = $data['created_at'];
    }
}
