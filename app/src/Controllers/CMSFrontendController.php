<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class CMSFrontendController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function renderDocument(string $slug): string
    {
        // Get the document by slug
        $document = Database::fetchOne(
            "SELECT d.*, b.name as blueprint_name, b.handle as blueprint_handle 
             FROM cms_documents d 
             LEFT JOIN cms_blueprints b ON d.blueprint_id = b.id 
             WHERE d.slug = ? AND d.status = 'published'",
            [$slug]
        );

        if (!$document) {
            // Return 404 or redirect to home
            header('HTTP/1.0 404 Not Found');
            return $this->render('errors/404', ['title' => 'Page Not Found'], 'main');
        }

        // Parse the document content
        $content = json_decode($document['content'], true);
        $blocks = $content['blocks'] ?? [];

        // For home page, use a special layout that doesn't include header
        if ($slug === '/' || $slug === 'home') {
            // Render each block
            $renderedContent = '';
            foreach ($blocks as $blockData) {
                $renderedContent .= $this->renderBlock($blockData['type'], $blockData['data']);
            }

            // Prepare data for the view
            $data = [
                'title' => $document['meta_title'] ?? $document['title'],
                'meta_description' => $document['meta_description'] ?? '',
                'page_title' => $document['title'],
                'content' => $renderedContent,
                'document' => $document
            ];

            return $this->render('cms/document', $data, 'main');
        }

        // For other pages, skip hero blocks and render normally
        $renderedContent = '';
        foreach ($blocks as $blockData) {
            // Skip hero blocks for non-home pages since header is included by layout
            if ($blockData['type'] === 'hero_section') {
                continue;
            }
            $renderedContent .= $this->renderBlock($blockData['type'], $blockData['data']);
        }

        // Prepare data for the view
        $data = [
            'title' => $document['meta_title'] ?? $document['title'],
            'meta_description' => $document['meta_description'] ?? '',
            'page_title' => $document['title'],
            'content' => $renderedContent,
            'document' => $document
        ];

        return $this->render('cms/document', $data, 'main');
    }

    private function renderBlock(string $blockHandle, array $data): string
    {
        // Get the block template
        $block = Database::fetchOne(
            "SELECT * FROM cms_blocks WHERE handle = ?",
            [$blockHandle]
        );

        if (!$block) {
            return "<!-- Block '{$blockHandle}' not found -->";
        }

        $template = $block['template'];

        // Replace template variables with data
        foreach ($data as $key => $value) {
            // Handle different value types
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            $template = str_replace('{{' . $key . '}}', htmlspecialchars((string)$value), $template);
        }

        // Remove any remaining unreplaced template variables
        $template = preg_replace('/\{\{[^}]+\}\}/', '', $template);

        return $template;
    }

    public function home(): string
    {
        // Get the home document
        $document = Database::fetchOne(
            "SELECT d.*, b.name as blueprint_name, b.handle as blueprint_handle 
             FROM cms_documents d 
             LEFT JOIN cms_blueprints b ON d.blueprint_id = b.id 
             WHERE d.slug = '/' AND d.status = 'published'"
        );

        if (!$document) {
            header('HTTP/1.0 404 Not Found');
            return $this->render('errors/404', ['title' => 'Page Not Found'], 'main');
        }

        // Parse the document content
        $content = json_decode($document['content'], true);
        $blocks = $content['blocks'] ?? [];

        // Render all blocks including hero
        $renderedContent = '';
        foreach ($blocks as $blockData) {
            $renderedContent .= $this->renderBlock($blockData['type'], $blockData['data']);
        }

        // Prepare data for the view
        $data = [
            'title' => $document['meta_title'] ?? $document['title'],
            'meta_description' => $document['meta_description'] ?? '',
            'content' => $renderedContent,
            'document' => $document
        ];

        return $this->render('cms/document', $data, 'main');
    }

    public function services(): string
    {
        // Try to render a services document, fallback to static view
        $document = Database::fetchOne(
            "SELECT * FROM cms_documents WHERE slug = 'services' AND status = 'published'"
        );

        if ($document) {
            return $this->renderDocument('services');
        }

        // No CMS document found
        header('HTTP/1.0 404 Not Found');
        return $this->render('errors/404', ['title' => 'Page Not Found'], 'main');
    }

    public function contact(): string
    {
        // Try to render a contact document, fallback to static view
        $document = Database::fetchOne(
            "SELECT * FROM cms_documents WHERE slug = 'contact' AND status = 'published'"
        );

        if ($document) {
            return $this->renderDocument('contact');
        }

        // No CMS document found
        header('HTTP/1.0 404 Not Found');
        return $this->render('errors/404', ['title' => 'Page Not Found'], 'main');
    }

    public function renderAbout(): string
    {
        return $this->renderDocument('about');
    }

    public function renderPricing(): string
    {
        return $this->renderDocument('pricing');
    }

    public function renderTestimonials(): string
    {
        return $this->renderDocument('testimonials');
    }
}