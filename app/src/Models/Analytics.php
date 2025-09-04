<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Core\Security;

class Analytics extends Model
{
    protected static string $table = 'website_analytics';
    protected static array $fillable = ['page_url', 'page_title', 'user_agent', 'ip_address', 'referrer', 'session_id', 'visit_date', 'visit_time'];
    protected static array $allowHtml = [];
    public static function track(string $pageUrl, string $pageTitle = '', string $referrer = ''): void
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $sessionId = session_id() ?: 'anonymous_' . uniqid();
        
        Database::insert('website_analytics', [
            'page_url' => $pageUrl,
            'page_title' => $pageTitle,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
            'referrer' => $referrer,
            'session_id' => $sessionId,
            'visit_date' => date('Y-m-d'),
            'visit_time' => date('H:i:s')
        ]);
    }

    public static function getTodayVisits(): int
    {
        $result = Database::fetchOne(
            "SELECT COUNT(*) as count FROM website_analytics WHERE visit_date = date('now')"
        );
        return (int)($result['count'] ?? 0);
    }

    public static function getUniqueVisitorsToday(): int
    {
        $result = Database::fetchOne(
            "SELECT COUNT(DISTINCT session_id) as count FROM website_analytics WHERE visit_date = date('now')"
        );
        return (int)($result['count'] ?? 0);
    }

    public static function getPageViews(int $days = 7): array
    {
        return Database::fetchAll(
            "SELECT visit_date, COUNT(*) as views 
             FROM website_analytics 
             WHERE visit_date >= date('now', '-{$days} days')
             GROUP BY visit_date 
             ORDER BY visit_date ASC"
        );
    }

    public static function getPopularPages(int $days = 7): array
    {
        return Database::fetchAll(
            "SELECT page_url, page_title, COUNT(*) as views 
             FROM website_analytics 
             WHERE visit_date >= date('now', '-{$days} days')
             GROUP BY page_url, page_title 
             ORDER BY views DESC 
             LIMIT 10"
        );
    }

    public static function getTrafficSources(int $days = 7): array
    {
        return Database::fetchAll(
            "SELECT 
                CASE 
                    WHEN referrer LIKE '%google%' THEN 'Google'
                    WHEN referrer LIKE '%facebook%' THEN 'Facebook'
                    WHEN referrer LIKE '%bing%' THEN 'Bing'
                    WHEN referrer LIKE '%yahoo%' THEN 'Yahoo'
                    WHEN referrer = '' OR referrer IS NULL THEN 'Direct'
                    ELSE 'Other'
                END as source,
                COUNT(*) as visits
             FROM website_analytics 
             WHERE visit_date >= date('now', '-{$days} days')
             GROUP BY source 
             ORDER BY visits DESC"
        );
    }

    public static function getHourlyTraffic(): array
    {
        return Database::fetchAll(
            "SELECT 
                strftime('%H', visit_time) as hour,
                COUNT(*) as visits
             FROM website_analytics 
             WHERE visit_date = date('now')
             GROUP BY hour 
             ORDER BY hour ASC"
        );
    }

    public static function getDeviceTypes(int $days = 7): array
    {
        return Database::fetchAll(
            "SELECT 
                CASE 
                    WHEN user_agent LIKE '%Mobile%' OR user_agent LIKE '%Android%' OR user_agent LIKE '%iPhone%' THEN 'Mobile'
                    WHEN user_agent LIKE '%Tablet%' OR user_agent LIKE '%iPad%' THEN 'Tablet'
                    ELSE 'Desktop'
                END as device_type,
                COUNT(*) as visits
             FROM website_analytics 
             WHERE visit_date >= date('now', '-{$days} days')
             GROUP BY device_type 
             ORDER BY visits DESC"
        );
    }

    public static function getWeeklyStats(): array
    {
        return Database::fetchAll(
            "SELECT 
                visit_date,
                COUNT(*) as total_visits,
                COUNT(DISTINCT session_id) as unique_visitors
             FROM website_analytics 
             WHERE visit_date >= date('now', '-7 days')
             GROUP BY visit_date 
             ORDER BY visit_date ASC"
        );
    }
}