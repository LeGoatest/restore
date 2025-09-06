<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\DTOs\SiteBenefitDTO;

class SiteBenefit extends Model
{
    protected static string $table = 'site_benefits';
    protected static array $fillable = ['title', 'description', 'icon', 'sort_order'];

    public static function all(): array
    {
        $allData = Database::fetchAll("SELECT * FROM " . self::$table . " ORDER BY sort_order");
        return array_map(fn($data) => new SiteBenefitDTO($data), $allData);
    }
}
