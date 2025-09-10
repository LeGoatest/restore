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

    public static function all(?int $userId = null): array
    {
        $allData = parent::all($userId);
        // The parent `all` method already sorts by primary key, so we'll rely on that for now.
        // If specific ordering is needed, it should be handled carefully.
        return array_map(fn($data) => new SiteBenefitDTO($data), $allData);
    }
}
