<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;

class CMSController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard(): string
    {
        Auth::requireAuth();
        
        $primitives = Database::fetchAll("SELECT * FROM cms_primitives ORDER BY name");
        $blocks = Database::fetchAll("SELECT * FROM cms_blocks ORDER BY name");
        $blueprints = Database::fetchAll("SELECT * FROM cms_blueprints ORDER BY name");
        $documents = Database::fetchAll("SELECT * FROM cms_documents ORDER BY created_at DESC LIMIT 10");

        $data = [
            'title' => 'CMS Dashboard - Restore Removal',
            'page_title' => 'Content Management System',
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'primitives' => $primitives,
            'blocks' => $blocks,
            'blueprints' => $blueprints,
            'documents' => $documents
        ];

        return $this->render('admin/cms/dashboard', $data, 'admin');
    }

    public function systemBuilder(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'System Builder - CMS',
            'page_title' => 'System Builder',
            'current_page' => 'cms',
            'username' => Auth::getUsername()
        ];
        
        return $this->render('admin/cms/system-builder', $data, 'admin');
    }

    public function createBlock()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $handle = strtolower(str_replace(' ', '_', $name));
            $block_type = $_POST['block_type'] ?? 'content';
            
            // Handle schema - could be JSON string from JavaScript or array from form
            $schema = $_POST['schema'] ?? [];
            if (is_string($schema)) {
                // Already JSON from JavaScript
                $schema_json = $schema;
            } else {
                // Convert array to JSON
                $schema_json = json_encode($schema);
            }
            
            $template = $_POST['template'] ?? '';

            $block_id = Database::insert('cms_blocks', [
                'name' => $name,
                'handle' => $handle,
                'schema' => $schema_json,
                'template' => $template,
                'block_type' => $block_type
            ]);

            // Redirect to block builder for further customization
            header('Location: /admin/cms/blocks/builder?id=' . $block_id);
            exit;
        }

        Auth::requireAuth();
        
        $data = [
            'title' => 'Create Block - CMS',
            'page_title' => 'Create Block',
            'current_page' => 'cms',
            'username' => Auth::getUsername()
        ];
        
        return $this->render('admin/cms/create-block', $data, 'admin');
    }

    public function createBlueprint()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $handle = strtolower(str_replace(' ', '_', $name));
            $schema = json_encode($_POST['schema'] ?? []);
            $allowed_blocks = json_encode($_POST['allowed_blocks'] ?? []);

            Database::insert('cms_blueprints', [
                'name' => $name,
                'handle' => $handle,
                'schema' => $schema,
                'allowed_blocks' => $allowed_blocks
            ]);

            header('Location: /admin/cms/system-builder');
            exit;
        }

        $blocks = Database::fetchAll("SELECT * FROM cms_blocks ORDER BY name");
        Auth::requireAuth();
        
        $data = [
            'title' => 'Create Blueprint - CMS',
            'page_title' => 'Create Blueprint',
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'blocks' => $blocks
        ];
        
        return $this->render('admin/cms/create-blueprint', $data, 'admin');
    }

    public function createDocument()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $blueprint_id = $_POST['blueprint_id'];
            
            // Handle content - if it's already JSON string, don't double-encode
            $content = $_POST['content'] ?? '{}';
            if (!is_string($content)) {
                $content = json_encode($content);
            } else {
                // Validate that it's valid JSON
                $decoded = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // If invalid JSON, treat as empty object
                    $content = '{}';
                }
            }
            
            $slug = strtolower(str_replace(' ', '-', $title));

            Database::insert('cms_documents', [
                'title' => $title,
                'slug' => $slug,
                'blueprint_id' => $blueprint_id,
                'content' => $content,
                'status' => 'draft'
            ]);

            header('Location: /admin/cms');
            exit;
        }

        $blueprints = Database::fetchAll("SELECT * FROM cms_blueprints ORDER BY name");
        Auth::requireAuth();
        
        $data = [
            'title' => 'Create Document - CMS',
            'page_title' => 'Create Document',
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'blueprints' => $blueprints
        ];
        
        return $this->render('admin/cms/create-document', $data, 'admin');
    }

    public function documents(): string
    {
        Auth::requireAuth();
        
        $documents = Database::fetchAll("
            SELECT d.*, b.name as blueprint_name 
            FROM cms_documents d 
            LEFT JOIN cms_blueprints b ON d.blueprint_id = b.id 
            ORDER BY d.created_at DESC
        ");

        $data = [
            'title' => 'Documents - CMS',
            'page_title' => 'All Documents',
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'documents' => $documents
        ];

        return $this->render('admin/cms/documents', $data, 'admin');
    }

    public function blocks(): string
    {
        Auth::requireAuth();
        
        $blocks = Database::fetchAll("SELECT * FROM cms_blocks ORDER BY name");

        $data = [
            'title' => 'Blocks - CMS',
            'page_title' => 'All Blocks',
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'blocks' => $blocks
        ];

        return $this->render('admin/cms/blocks', $data, 'admin');
    }

    public function blueprints(): string
    {
        Auth::requireAuth();
        
        $blueprints = Database::fetchAll("SELECT * FROM cms_blueprints ORDER BY name");

        $data = [
            'title' => 'Blueprints - CMS',
            'page_title' => 'All Blueprints',
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'blueprints' => $blueprints
        ];

        return $this->render('admin/cms/blueprints', $data, 'admin');
    }

    public function editDocument(): string
    {
        Auth::requireAuth();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/documents');
            exit;
        }

        $document = Database::fetchOne("
            SELECT d.*, b.name as blueprint_name, b.schema as blueprint_schema, b.allowed_blocks 
            FROM cms_documents d 
            LEFT JOIN cms_blueprints b ON d.blueprint_id = b.id 
            WHERE d.id = ?
        ", [$id]);

        if (!$document) {
            header('Location: /admin/cms/documents');
            exit;
        }

        $blueprints = Database::fetchAll("SELECT * FROM cms_blueprints ORDER BY name");
        $blocks = Database::fetchAll("SELECT * FROM cms_blocks ORDER BY name");

        $data = [
            'title' => 'Edit Document - CMS',
            'page_title' => 'Edit Document: ' . $document['title'],
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'document' => $document,
            'blueprints' => $blueprints,
            'blocks' => $blocks
        ];

        return $this->render('admin/cms/edit-document', $data, 'admin');
    }

    public function updateDocument(): void
    {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/cms/documents');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/documents');
            exit;
        }

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? strtolower(str_replace(' ', '-', $title));
        $blueprint_id = $_POST['blueprint_id'] ?? null;
        
        // Handle content - if it's already JSON string, don't double-encode
        $content = $_POST['content'] ?? '{}';
        if (!is_string($content)) {
            $content = json_encode($content);
        } else {
            // Validate that it's valid JSON
            $decoded = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If invalid JSON, treat as empty object
                $content = '{}';
            }
        }
        
        $status = $_POST['status'] ?? 'draft';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';

        Database::query("
            UPDATE cms_documents 
            SET title = ?, slug = ?, blueprint_id = ?, content = ?, status = ?, 
                meta_title = ?, meta_description = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ", [$title, $slug, $blueprint_id, $content, $status, $meta_title, $meta_description, $id]);

        header('Location: /admin/cms/documents');
        exit;
    }

    public function deleteDocument(): void
    {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/cms/documents');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/documents');
            exit;
        }

        Database::query("DELETE FROM cms_documents WHERE id = ?", [$id]);

        header('Location: /admin/cms/documents');
        exit;
    }

    public function editBlock(): string
    {
        Auth::requireAuth();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $block = Database::fetchOne("SELECT * FROM cms_blocks WHERE id = ?", [$id]);
        if (!$block) {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $data = [
            'title' => 'Edit Block - CMS',
            'page_title' => 'Edit Block: ' . $block['name'],
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'block' => $block
        ];

        return $this->render('admin/cms/edit-block', $data, 'admin');
    }

    public function blockBuilder(): string
    {
        Auth::requireAuth();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/blocks');
            exit;
        }

        $block = Database::fetchOne("SELECT * FROM cms_blocks WHERE id = ?", [$id]);
        if (!$block) {
            header('Location: /admin/cms/blocks');
            exit;
        }

        $data = [
            'title' => 'Block Builder - ' . $block['name'],
            'page_title' => 'Block Builder',
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'block' => $block
        ];

        return $this->render('admin/cms/block-builder', $data, 'minimal');
    }

    public function updateBlock(): void
    {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $handle = $_POST['handle'] ?? strtolower(str_replace(' ', '_', $name));
        $schema = json_encode($_POST['schema'] ?? []);
        $template = $_POST['template'] ?? '';

        Database::query("
            UPDATE cms_blocks 
            SET name = ?, handle = ?, schema = ?, template = ? 
            WHERE id = ?
        ", [$name, $handle, $schema, $template, $id]);

        header('Location: /admin/cms/system-builder');
        exit;
    }

    public function deleteBlock(): void
    {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        Database::query("DELETE FROM cms_blocks WHERE id = ?", [$id]);

        header('Location: /admin/cms/system-builder');
        exit;
    }

    public function editBlueprint(): string
    {
        Auth::requireAuth();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $blueprint = Database::fetchOne("SELECT * FROM cms_blueprints WHERE id = ?", [$id]);
        if (!$blueprint) {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $blocks = Database::fetchAll("SELECT * FROM cms_blocks ORDER BY name");

        $data = [
            'title' => 'Edit Blueprint - CMS',
            'page_title' => 'Edit Blueprint: ' . $blueprint['name'],
            'current_page' => 'cms',
            'username' => Auth::getUsername(),
            'blueprint' => $blueprint,
            'blocks' => $blocks
        ];

        return $this->render('admin/cms/edit-blueprint', $data, 'admin');
    }

    public function updateBlueprint(): void
    {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $handle = $_POST['handle'] ?? strtolower(str_replace(' ', '_', $name));
        $schema = json_encode($_POST['schema'] ?? []);
        $allowed_blocks = json_encode($_POST['allowed_blocks'] ?? []);

        Database::query("
            UPDATE cms_blueprints 
            SET name = ?, handle = ?, schema = ?, allowed_blocks = ? 
            WHERE id = ?
        ", [$name, $handle, $schema, $allowed_blocks, $id]);

        header('Location: /admin/cms/system-builder');
        exit;
    }

    public function deleteBlueprint(): void
    {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/cms/system-builder');
            exit;
        }

        Database::query("DELETE FROM cms_blueprints WHERE id = ?", [$id]);

        header('Location: /admin/cms/system-builder');
        exit;
    }
}