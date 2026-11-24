<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;
use Exception;

class SetupDatabaseCommand extends Command
{
    protected $signature = 'app:setup-db {--fresh : Wipe existing tables and run fresh migrations}';
    protected $description = 'Create database if needed, execute migrations, and seed Perlcon International marketplace data';

    public function handle()
    {
        $this->info("Initializing Perlcon International Database Setup...");

        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");

        try {
            if ($connection === 'mysql') {
                $host = $dbConfig['host'] ?? '127.0.0.1';
                $port = $dbConfig['port'] ?? '3306';
                $user = $dbConfig['username'] ?? 'root';
                $pass = $dbConfig['password'] ?? '';
                $dbname = $dbConfig['database'] ?? 'laravel';

                $this->line("Connecting to MySQL at {$host}:{$port}...");
                $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);

                $this->line("Ensuring database `{$dbname}` exists...");
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $this->info("Database `{$dbname}` ready.");
            } elseif ($connection === 'sqlite') {
                $dbPath = $dbConfig['database'] ?? database_path('database.sqlite');
                if (!file_exists($dbPath)) {
                    touch($dbPath);
                    $this->info("Created SQLite file at {$dbPath}");
                }
            }

            $fresh = $this->option('fresh');
            if ($fresh) {
                $this->line("Running migrate:fresh...");
                Artisan::call('migrate:fresh', ['--force' => true]);
            } else {
                $this->line("Running migrate...");
                Artisan::call('migrate', ['--force' => true]);
            }
            $this->info(trim(Artisan::output()));

            $this->line("Seeding demo marketplace data...");
            Artisan::call('db:seed', ['--force' => true]);
            $this->info(trim(Artisan::output()));

            $this->info("Perlcon International B2B Marketplace database setup completed successfully!");
            $this->table(
                ['Role', 'Email', 'Password'],
                [
                    ['Super Admin', 'admin@perlcon.com', 'password123'],
                    ['Supplier (India)', 'supplier.india@perlcon.com', 'password123'],
                    ['Supplier (China)', 'supplier.china@perlcon.com', 'password123'],
                    ['Supplier (Germany)', 'supplier.europe@perlcon.com', 'password123'],
                    ['UK Buyer', 'buyer@perlcon.com', 'password123'],
                    ['Logistics Partner', 'logistics@perlcon.com', 'password123'],
                    ['Customs Partner', 'customs@perlcon.com', 'password123'],
                ]
            );

            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error("Setup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
