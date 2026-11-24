<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;
use Exception;

class DatabaseSetupController extends Controller
{
    /**
     * Show the Database Setup UI.
     */
    public function index()
    {
        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");

        $connectionStatus = 'Unknown';
        $connectionMessage = '';
        $databaseExists = false;
        $tablesCount = 0;

        try {
            if ($connection === 'mysql') {
                $host = $dbConfig['host'] ?? '127.0.0.1';
                $port = $dbConfig['port'] ?? '3306';
                $user = $dbConfig['username'] ?? 'root';
                $pass = $dbConfig['password'] ?? '';
                $dbname = $dbConfig['database'] ?? 'laravel';

                // First test connection to host
                $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5,
                ]);

                $connectionStatus = 'Connected to MySQL Host';

                // Check if database exists
                $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$dbname}'");
                if ($stmt && $stmt->fetch()) {
                    $databaseExists = true;
                    // Count tables
                    $pdoWithDb = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $user, $pass);
                    $stmtTables = $pdoWithDb->query("SHOW TABLES");
                    $tablesCount = count($stmtTables->fetchAll());
                    $connectionMessage = "Database '{$dbname}' exists with {$tablesCount} tables.";
                } else {
                    $connectionMessage = "Database '{$dbname}' does not exist yet. It will be created automatically when you click the setup button!";
                }
            } elseif ($connection === 'sqlite') {
                $dbPath = $dbConfig['database'] ?? database_path('database.sqlite');
                if (file_exists($dbPath)) {
                    $databaseExists = true;
                    $pdo = new PDO("sqlite:{$dbPath}");
                    $stmt = $pdo->query("SELECT count(*) FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                    $tablesCount = (int) $stmt->fetchColumn();
                    $connectionStatus = 'Connected to SQLite';
                    $connectionMessage = "SQLite database file exists with {$tablesCount} tables.";
                } else {
                    $connectionStatus = 'Ready to Create';
                    $connectionMessage = "SQLite database file will be created automatically.";
                }
            }
        } catch (Exception $e) {
            $connectionStatus = 'Connection Pending / Error';
            $connectionMessage = $e->getMessage();
        }

        return view('setup.index', compact('connection', 'dbConfig', 'connectionStatus', 'connectionMessage', 'databaseExists', 'tablesCount'));
    }

    /**
     * Execute Database Creation, Migrations, and Seeders.
     */
    public function execute(Request $request)
    {
        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");
        $forceFresh = true; // Always perform clean fresh migration and seeding
        $log = [];

        try {
            // Step 1: Create Database if MySQL and not exists
            if ($connection === 'mysql') {
                $host = $dbConfig['host'] ?? '127.0.0.1';
                $port = $dbConfig['port'] ?? '3306';
                $user = $dbConfig['username'] ?? 'root';
                $pass = $dbConfig['password'] ?? '';
                $dbname = $dbConfig['database'] ?? 'laravel';

                $log[] = "Connecting to MySQL server at {$host}:{$port}...";
                $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);

                if ($forceFresh) {
                    $log[] = "Re-creating fresh MySQL database `{$dbname}`...";
                    $pdo->exec("DROP DATABASE IF EXISTS `{$dbname}`;");
                    $pdo->exec("CREATE DATABASE `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                    DB::purge('mysql');
                    $log[] = "✓ Database `{$dbname}` is recreated fresh and ready.";
                } else {
                    $log[] = "Creating database `{$dbname}` if not exists...";
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                    $log[] = "✓ Database `{$dbname}` is verified and ready.";
                    DB::purge('mysql');
                }
            } elseif ($connection === 'sqlite') {
                $dbPath = $dbConfig['database'] ?? database_path('database.sqlite');
                DB::disconnect();
                if (file_exists($dbPath)) {
                    @unlink($dbPath);
                }
                File::ensureDirectoryExists(dirname($dbPath));
                touch($dbPath);
                DB::purge('sqlite');
                $log[] = "✓ Re-initialized clean SQLite database file at {$dbPath}";
            }

            // Step 2: Run Migrations
            $log[] = "Running database migrations...";
            Artisan::call('migrate:fresh', ['--force' => true]);
            $log[] = "✓ Migrations executed (Fresh): " . trim(Artisan::output());

            // Step 3: Run Seeders
            $log[] = "Seeding demo marketplace data (Admin, Suppliers, Buyers, Categories, Products, RFQs, Logistics, Customs)...";
            Artisan::call('db:seed', ['--force' => true]);
            $log[] = "✓ Seeding complete: " . trim(Artisan::output());

            // Step 4: Storage Symlink
            try {
                Artisan::call('storage:link');
                $log[] = "✓ Public storage symlink verified.";
            } catch (Exception $e) {
                $log[] = "Storage link notice: " . $e->getMessage();
            }

            // Step 5: Clear and cache configs
            try {
                Artisan::call('optimize:clear');
                $log[] = "✓ Application cache optimized.";
            } catch (Exception $e) {
                // non-critical
            }

            $success = true;
            $message = 'Perlcon International B2B Marketplace database has been successfully cleaned and fresh seeded!';
        } catch (Exception $e) {
            $success = false;
            $message = 'Database Setup Encountered An Error: ' . $e->getMessage();
            $log[] = 'ERROR: ' . $e->getMessage();
        }

        if ($request->expectsJson() || $request->has('json')) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'log' => $log,
            ]);
        }

        return view('setup.result', compact('success', 'message', 'log'));
    }

    /**
     * Direct 1-Click Route: Visit /setup-db/quick or /clean-db to run migrations and seeder immediately
     */
    public function quickRun(Request $request)
    {
        $request->merge(['force_fresh' => true]);
        return $this->execute($request);
    }
}
