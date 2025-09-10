<?php

declare(strict_types=1);

namespace App\Core;

class Migration
{
    private static string $migrationsPath;

    public static function init(string $migrationsPath = null): void
    {
        self::$migrationsPath = $migrationsPath ?? __DIR__ . '/../../database/migrations';
    }

    public static function run(): void
    {
        // Create migrations tracking table
        self::createMigrationsTable();

        // Get all migration files
        $migrationFiles = glob(self::$migrationsPath . '/*.sql');
        sort($migrationFiles);

        foreach ($migrationFiles as $file) {
            $filename = basename($file);
            
            // Check if migration has already been run
            if (self::hasBeenRun($filename)) {
                continue;
            }

            echo "Running migration: {$filename}\n";
            
            try {
                $sql = file_get_contents($file);
                Database::getConnection()->exec($sql);
                
                // Record that this migration has been run
                self::recordMigration($filename);
                
                echo "✓ Migration {$filename} completed successfully\n";
                
            } catch (\Exception $e) {
                echo "✗ Migration {$filename} failed: " . $e->getMessage() . "\n";
                throw $e;
            }
        }

        echo "All migrations completed!\n";
    }

    private static function createMigrationsTable(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                filename VARCHAR(255) NOT NULL UNIQUE,
                executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        Database::getConnection()->exec($sql);
    }

    private static function hasBeenRun(string $filename): bool
    {
        $result = Database::fetchOne(
            "SELECT id FROM migrations WHERE filename = ?",
            [$filename]
        );
        
        return $result !== null;
    }

    private static function recordMigration(string $filename): void
    {
        Database::insert('migrations', [
            'filename' => $filename
        ]);
    }

    public static function reset(): void
    {
        echo "Resetting database...\n";
        
        // Get all tables except migrations and sqlite_sequence
        $tables = Database::fetchAll(
            "SELECT name FROM sqlite_master WHERE type='table' AND name != 'migrations' AND name != 'sqlite_sequence'"
        );
        
        foreach ($tables as $table) {
            Database::query("DROP TABLE IF EXISTS " . $table['name']);
            echo "Dropped table: {$table['name']}\n";
        }
        
        // Clear migrations record
        Database::query("DELETE FROM migrations");
        
        echo "Database reset complete!\n";
    }
}