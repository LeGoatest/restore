<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Hero extends Model
{
    protected static string $table = 'hero';
    
    /**
     * Get the current hero section content
     * @return array The hero section data
     */
    public static function getCurrent(): array
    {
        $sql = "SELECT * FROM hero ORDER BY id DESC LIMIT 1";
        $stmt = Database::query($sql);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [
            'title' => 'Professional Junk Removal Services',
            'subtitle' => 'Hassle-Free Cleanouts in Central Florida',
            'description' => 'From single items to full property cleanouts, we handle it all with professional service and environmental responsibility.',
            'background_type' => 'image',
            'background_value' => '/static/images/hero-bg.jpg',
            'overlay_opacity' => 50,
            'text_alignment' => 'center',
            'animation_style' => 'fade',
            'cta_text' => 'Get a Free Quote',
            'cta_url' => '/quote'
        ];
    }

    /**
     * Save hero section content
     * @param array $data The hero data to save
     * @return bool Whether the save was successful
     */
    public static function save(array $data): bool
    {
        // Handle file upload if present
        if (isset($_FILES['hero']['background_file']) && $_FILES['hero']['background_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['hero']['background_file'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowedTypes)) {
                return false;
            }
            
            // Generate unique filename
            $filename = 'hero-' . uniqid() . '.' . $extension;
            $uploadPath = 'public_html/static/images/' . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $data['background_value'] = '/static/images/' . $filename;
            }
        }

        // Clean and validate data
        $cleanData = [
            'title' => trim($data['title'] ?? ''),
            'subtitle' => trim($data['subtitle'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'background_type' => $data['background_type'] ?? 'image',
            'background_value' => $data['background_value'] ?? '',
            'overlay_opacity' => min(100, max(0, intval($data['overlay_opacity'] ?? 50))),
            'text_alignment' => in_array($data['text_alignment'] ?? '', ['left', 'center', 'right']) ? $data['text_alignment'] : 'center',
            'animation_style' => in_array($data['animation_style'] ?? '', ['none', 'fade', 'slide', 'zoom']) ? $data['animation_style'] : 'fade',
            'cta_text' => trim($data['cta_text'] ?? ''),
            'cta_url' => trim($data['cta_url'] ?? ''),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Insert new record
        $sql = "INSERT INTO hero (
            title, subtitle, description, background_type, background_value,
            overlay_opacity, text_alignment, animation_style, cta_text, cta_url, 
            updated_at
        ) VALUES (
            :title, :subtitle, :description, :background_type, :background_value,
            :overlay_opacity, :text_alignment, :animation_style, :cta_text, :cta_url,
            :updated_at
        )";

        try {
            return Database::query($sql, $cleanData)->rowCount() > 0;
        } catch (\PDOException $e) {
            error_log("Error saving hero: " . $e->getMessage());
            return false;
        }
    }
}