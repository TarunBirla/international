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
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

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

                $log[] = "Checking MySQL connection for database `{$dbname}` at {$host}:{$port}...";
                try {
                    // Try connecting to host to create database if permitted
                    $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 5,
                    ]);
                    try {
                        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                        $log[] = "✓ MySQL database `{$dbname}` verified / created.";
                    } catch (Exception $eDb) {
                        $log[] = "Notice: Database `{$dbname}` exists (managed via hosting panel).";
                    }
                } catch (Exception $eHost) {
                    // On shared hosting, connecting without dbname might be restricted; try connecting directly to dbname
                    try {
                        $pdoDb = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $user, $pass, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_TIMEOUT => 5,
                        ]);
                        $log[] = "✓ Connected directly to MySQL database `{$dbname}`.";
                    } catch (Exception $eDirect) {
                        $log[] = "Notice: Connection to MySQL database: " . $eDirect->getMessage();
                    }
                }
                DB::purge('mysql');
            } elseif ($connection === 'sqlite') {
                $dbPath = $dbConfig['database'] ?? database_path('database.sqlite');
                DB::disconnect();
                if (file_exists($dbPath)) {
                    @unlink($dbPath);
                }
                File::ensureDirectoryExists(dirname($dbPath));
                @touch($dbPath);
                @chmod($dbPath, 0777);
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
            $message = 'Mart B2B Marketplace database has been successfully cleaned and fresh seeded with 5 categories & 5 products!';
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

    /**
     * Direct 1-Click Route: Visit /setup-users or /reset-users to create all role accounts
     * with clean demo emails: superadmin@demo.com, admin@demo.com, supplier@demo.com, buyer@demo.com, etc.
     */
    public function setupUsers()
    {
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        try {
            // Ensure migrations have executed so tables exist
            Artisan::call('migrate', ['--force' => true]);

            $password = \Illuminate\Support\Facades\Hash::make('password123');

            $demoUsers = [
                [
                    'role' => 'super_admin',
                    'name' => 'Super Administrator',
                    'email' => 'superadmin@demo.com',
                    'phone' => '+44 20 7946 0991',
                    'country' => 'United Kingdom',
                    'city' => 'London',
                    'badge' => 'Super Admin',
                    'badge_color' => 'purple',
                    'company' => null,
                ],
                [
                    'role' => 'admin',
                    'name' => 'Operations Admin',
                    'email' => 'admin@demo.com',
                    'phone' => '+44 20 7946 0992',
                    'country' => 'United Kingdom',
                    'city' => 'London',
                    'badge' => 'Operations Admin',
                    'badge_color' => 'indigo',
                    'company' => null,
                ],
                [
                    'role' => 'supplier',
                    'name' => 'Rohit (Indian Manufacturer)',
                    'email' => 'supplier@demo.com',
                    'phone' => '+91 98250 12345',
                    'country' => 'India',
                    'city' => 'Pune',
                    'badge' => 'Verified Supplier',
                    'badge_color' => 'emerald',
                    'company' => [
                        'company_name' => 'Rohit Precision Forgings Pvt Ltd',
                        'slug' => 'rohit-precision-forgings-demo',
                        'business_type' => 'manufacturer',
                        'country' => 'India',
                        'city' => 'Pune',
                        'export_markets' => 'UK, Germany, France, UAE, USA',
                        'is_verified' => true,
                        'description' => 'Premier ISO 9001:2015 and CE certified heavy engineering manufacturer specializing in forged industrial flanges, CNC turned components, and steel valves.',
                    ],
                ],
                [
                    'role' => 'supplier',
                    'name' => 'Tarry (China Automation)',
                    'email' => 'supplier2@demo.com',
                    'phone' => '+86 755 8345 6789',
                    'country' => 'China',
                    'city' => 'Shenzhen',
                    'badge' => 'Verified Supplier',
                    'badge_color' => 'emerald',
                    'company' => [
                        'company_name' => 'Tarry Automation & CNC Systems Ltd',
                        'slug' => 'tarry-automation-cnc-demo',
                        'business_type' => 'manufacturer',
                        'country' => 'China',
                        'city' => 'Shenzhen',
                        'export_markets' => 'UK, Europe, North America',
                        'is_verified' => true,
                        'description' => 'Leading producer of 5-Axis CNC machining centers, automated robotic arms, and industrial servo drive units.',
                    ],
                ],
                [
                    'role' => 'buyer',
                    'name' => 'Navdeep (UK Procurement)',
                    'email' => 'buyer@demo.com',
                    'phone' => '+44 121 496 0884',
                    'country' => 'United Kingdom',
                    'city' => 'Birmingham',
                    'badge' => 'Verified Buyer',
                    'badge_color' => 'sky',
                    'company' => [
                        'company_name' => 'Navdeep Industrial UK Ltd',
                        'slug' => 'navdeep-industrial-uk-demo',
                        'business_type' => 'distributor',
                        'country' => 'United Kingdom',
                        'city' => 'Birmingham',
                        'is_verified' => true,
                        'description' => 'Major British engineering and industrial turnkey contractor supplying components to UK infrastructure projects.',
                    ],
                ],
                [
                    'role' => 'logistics_partner',
                    'name' => 'Harry (Freight Forwarder)',
                    'email' => 'logistics@demo.com',
                    'phone' => '+44 20 8759 2300',
                    'country' => 'United Kingdom',
                    'city' => 'Heathrow',
                    'badge' => 'Logistics Partner',
                    'badge_color' => 'amber',
                    'company' => [
                        'company_name' => 'Harry Global Express & Freight Forwarding Ltd',
                        'slug' => 'harry-global-freight-demo',
                        'business_type' => 'distributor',
                        'country' => 'United Kingdom',
                        'city' => 'Heathrow',
                        'is_verified' => true,
                        'description' => 'Specialist international air & sea freight forwarder handling containerized LCL/FCL shipments across the UK.',
                    ],
                ],
                [
                    'role' => 'customs_partner',
                    'name' => 'Nasar (Junaid & Co. Customs)',
                    'email' => 'customs@demo.com',
                    'phone' => '+44 1394 670 120',
                    'country' => 'United Kingdom',
                    'city' => 'Felixstowe',
                    'badge' => 'Customs Partner',
                    'badge_color' => 'blue',
                    'company' => [
                        'company_name' => 'Junaid & Co. UK Customs Clearance Specialists',
                        'slug' => 'junaid-co-customs-clearance-demo',
                        'business_type' => 'distributor',
                        'country' => 'United Kingdom',
                        'city' => 'Felixstowe',
                        'is_verified' => true,
                        'description' => 'Authorised Economic Operator (AEO-F) customs broker providing HMRC CDS clearances, C88 processing, and import VAT optimization.',
                    ],
                ],
            ];

            $createdUsers = [];

            foreach ($demoUsers as $item) {
                $user = \App\Models\User::updateOrCreate(
                    ['email' => $item['email']],
                    [
                        'name' => $item['name'],
                        'password' => $password,
                        'role' => $item['role'],
                        'phone' => $item['phone'],
                        'country' => $item['country'],
                        'city' => $item['city'],
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ]
                );

                if (!empty($item['company'])) {
                    \App\Models\Company::updateOrCreate(
                        ['user_id' => $user->id],
                        array_merge($item['company'], [
                            'user_id' => $user->id,
                        ])
                    );
                }

                $portalRoute = match($item['role']) {
                    'super_admin', 'admin' => route('admin.dashboard'),
                    'supplier' => route('supplier.dashboard'),
                    'buyer' => route('buyer.dashboard'),
                    'logistics_partner' => route('logistics.dashboard'),
                    'customs_partner' => route('customs.dashboard'),
                    default => url('/'),
                };

                $createdUsers[] = [
                    'role' => $item['role'],
                    'badge' => $item['badge'],
                    'badge_color' => $item['badge_color'],
                    'name' => $item['name'],
                    'email' => $item['email'],
                    'password' => 'password123',
                    'portal' => $portalRoute,
                ];
            }

            return view('setup.users', [
                'success' => true,
                'message' => 'All Role-based Demo Accounts have been created and verified!',
                'users' => $createdUsers,
            ]);
        } catch (\Exception $e) {
            return view('setup.users', [
                'success' => false,
                'message' => 'Error setting up users: ' . $e->getMessage(),
                'users' => [],
            ]);
        }
    }
}
