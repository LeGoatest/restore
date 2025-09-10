<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\DTOs\TestimonialDTO;

class Testimonial extends Model
{
    protected static string $table = 'testimonials';
    protected static array $fillable = ['author_name', 'author_location', 'quote_text', 'rating', 'is_featured', 'created_at'];

    public static function all(?int $userId = null): array
    {
        $allData = parent::all($userId);
        return array_map(fn($data) => new TestimonialDTO($data), $allData);
    }

    public static function where(string $column, mixed $value, ?int $userId = null): array
    {
        $allData = parent::where($column, $value, $userId);
        return array_map(fn($data) => new TestimonialDTO($data), $allData);
    }
}
