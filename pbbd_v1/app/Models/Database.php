<?php
// database.php

function getDB() {
    static $db = null;
    if ($db === null) {
        try {
            $db = new PDO('sqlite:' . __DIR__ . '/ducky.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $db;
}

function setupDatabase() {
    $db = getDB();
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='primitives'");
    if ($stmt->fetch()) return; // Already set up

    // Clean slate for demo purposes
    $db->exec("DROP TABLE IF EXISTS primitives;");
    $db->exec("DROP TABLE IF EXISTS blocks;");
    $db->exec("DROP TABLE IF EXISTS blueprints;");
    $db->exec("DROP TABLE IF EXISTS documents;");

    // Create tables
    $db->exec("CREATE TABLE primitives (id INTEGER PRIMARY KEY, handle TEXT UNIQUE, name TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $db->exec("CREATE TABLE blocks (id INTEGER PRIMARY KEY, handle TEXT UNIQUE, name TEXT, template TEXT, schema TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $db->exec("CREATE TABLE blueprints (id INTEGER PRIMARY KEY, handle TEXT UNIQUE, name TEXT, schema TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $db->exec("CREATE TABLE documents (id INTEGER PRIMARY KEY, blueprint_id INTEGER, slug TEXT UNIQUE, title TEXT, content TEXT, user_id INTEGER, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $db->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT UNIQUE, password TEXT, email TEXT UNIQUE, role_id INTEGER, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $db->exec("CREATE TABLE roles (id INTEGER PRIMARY KEY, name TEXT UNIQUE, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $db->exec("CREATE TABLE permissions (id INTEGER PRIMARY KEY, name TEXT UNIQUE, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $db->exec("CREATE TABLE role_permissions (role_id INTEGER, permission_id INTEGER, PRIMARY KEY (role_id, permission_id))");


    // Seed Primitives
    $db->prepare("INSERT INTO primitives (handle, name) VALUES (?, ?)")->execute(['text', 'Text Input']);
    $db->prepare("INSERT INTO primitives (handle, name) VALUES (?, ?)")->execute(['textarea', 'Text Area']);

    // Seed Roles
    $db->prepare("INSERT INTO roles (name) VALUES (?)")->execute(['admin']);
    $db->prepare("INSERT INTO roles (name) VALUES (?)")->execute(['editor']);

    // Seed Permissions
    $permissions = ['create_users', 'read_users', 'update_users', 'delete_users', 'create_roles', 'read_roles', 'update_roles', 'delete_roles', 'create_permissions', 'read_permissions', 'update_permissions', 'delete_permissions', 'create_documents', 'read_documents', 'update_documents', 'delete_documents'];
    foreach ($permissions as $permission) {
        $db->prepare("INSERT INTO permissions (name) VALUES (?)")->execute([$permission]);
    }

    // Assign all permissions to admin role
    $adminRoleId = $db->lastInsertId();
    for ($i = 1; $i <= $adminRoleId; $i++) {
        $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)")->execute([1, $i]);
    }

    // Seed a default admin user
    $username = 'admin';
    $password = password_hash('password', PASSWORD_DEFAULT);
    $email = 'admin@example.com';
    $role_id = 1; // admin
    $db->prepare("INSERT INTO users (username, password, email, role_id) VALUES (?, ?, ?, ?)")->execute([$username, $password, $email, $role_id]);


    // Seed Hero Block
    $heroSchema = json_encode(['fields' => [['handle' => 'headline', 'primitive_handle' => 'text', 'label' => 'Headline'], ['handle' => 'sub_headline', 'primitive_handle' => 'textarea', 'label' => 'Sub-Headline']]]);
    $heroTpl = '<div class="bg-slate-100 p-8 rounded-lg my-4 dark:bg-slate-800"><h1 class="text-4xl font-bold text-slate-800 dark:text-slate-100">{{ data.headline }}</h1><p class="text-xl text-slate-600 mt-2 dark:text-slate-300">{{ data.sub_headline }}</p></div>';
    $db->prepare("INSERT INTO blocks (handle, name, template, schema) VALUES (?, ?, ?, ?)")->execute(['hero', 'Hero Section', $heroTpl, $heroSchema]);

    // Seed Basic Page Blueprint
    $bpSchema = json_encode(['block_area' => ['allowed_blocks' => ['hero']]]);
    $db->prepare("INSERT INTO blueprints (handle, name, schema) VALUES (?, ?, ?)")->execute(['basic-page', 'Basic Page', $bpSchema]);
    $bpId = $db->lastInsertId();

    // Seed Document
    $docContent = json_encode(['blocks' => [['block_handle' => 'hero', 'data' => ['headline' => 'Welcome to DuckyCMS!', 'sub_headline' => 'This page is rendered entirely from the database.']]]]);
    $db->prepare("INSERT INTO documents (blueprint_id, slug, title, content) VALUES (?, ?, ?, ?)")->execute([$bpId, 'hello-world', 'Hello World Page', $docContent]);
}

setupDatabase();
