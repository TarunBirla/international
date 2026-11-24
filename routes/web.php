<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatabaseSetupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LandedCostCalculatorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\SupplierDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LogisticsDashboardController;
use App\Http\Controllers\CustomsDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes: Perlcon International B2B Marketplace
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. SERVER 1-CLICK DATABASE SETUP & MIGRATION ROUTES
// When uploaded to shared hosting / cPanel, hitting these routes creates the DB & seeds demo data!
// ==========================================
Route::get('/setup-db', [DatabaseSetupController::class, 'index'])->name('setup.index');
Route::post('/setup-db/execute', [DatabaseSetupController::class, 'execute'])->name('setup.execute');
Route::get('/setup-db/quick', [DatabaseSetupController::class, 'quickRun'])->name('setup.quick');
Route::get('/setup-db/run', [DatabaseSetupController::class, 'quickRun'])->name('setup.run');
Route::get('/install', [DatabaseSetupController::class, 'index'])->name('install');

// ==========================================
// 2. PUBLIC MARKETPLACE WEBSITE
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/shipping-logistics', [HomeController::class, 'shipping'])->name('shipping');
Route::get('/customs-clearance', [HomeController::class, 'customs'])->name('customs');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Suppliers Directory & Public Profile
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/{slug}', [SupplierController::class, 'show'])->name('suppliers.show');

// RFQ Public / Shared Views
Route::get('/rfqs', [RfqController::class, 'index'])->name('rfqs.index');
Route::get('/rfqs/{id}', [RfqController::class, 'show'])->name('rfqs.show');

// UK Import Duty & Landed Cost Calculator
Route::get('/import-duty-calculator', [LandedCostCalculatorController::class, 'index'])->name('calculator.index');
Route::post('/import-duty-calculator/calculate', [LandedCostCalculatorController::class, 'calculate'])->name('calculator.calculate');

// Enquiries
Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');

// Procurement Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// ==========================================
// 3. AUTHENTICATION & REGISTRATION
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 4. AUTHENTICATED COMMON ROUTES (Checkout, Messaging, Orders, RFQs)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Checkout & Order Creation
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // RFQ Creation & Management
    Route::get('/rfq/create', [RfqController::class, 'create'])->name('rfqs.create');
    Route::post('/rfq/store', [RfqController::class, 'store'])->name('rfqs.store');
    Route::post('/rfqs/{id}/submit-quotation', [RfqController::class, 'submitQuotation'])->name('rfqs.submit-quotation');
    Route::get('/rfqs/{id}/compare', [RfqController::class, 'compare'])->name('rfqs.compare');
    Route::post('/quotations/{id}/accept', [RfqController::class, 'acceptQuotation'])->name('quotations.accept');

    // Internal Messaging
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
});

// ==========================================
// 5. BUYER DASHBOARD
// ==========================================
Route::middleware(['auth', 'role:buyer,super_admin,admin'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/rfqs', [BuyerDashboardController::class, 'rfqs'])->name('rfqs');
    Route::get('/quotations', [BuyerDashboardController::class, 'quotations'])->name('quotations');
    Route::get('/enquiries', [BuyerDashboardController::class, 'enquiries'])->name('enquiries');
    Route::get('/orders', [BuyerDashboardController::class, 'orders'])->name('orders');
    Route::get('/profile', [BuyerDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [BuyerDashboardController::class, 'updateProfile'])->name('profile.update');
});

// ==========================================
// 6. SUPPLIER DASHBOARD
// ==========================================
Route::middleware(['auth', 'role:supplier,super_admin,admin'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('/products', [SupplierDashboardController::class, 'products'])->name('products');
    Route::get('/products/create', [SupplierDashboardController::class, 'createProduct'])->name('products.create');
    Route::post('/products/store', [SupplierDashboardController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [SupplierDashboardController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [SupplierDashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [SupplierDashboardController::class, 'deleteProduct'])->name('products.delete');

    // Enquiries, Quotes, Orders
    Route::get('/enquiries', [SupplierDashboardController::class, 'enquiries'])->name('enquiries');
    Route::get('/quotations', [SupplierDashboardController::class, 'quotations'])->name('quotations');
    Route::get('/orders', [SupplierDashboardController::class, 'orders'])->name('orders');
    Route::post('/orders/{id}/status', [SupplierDashboardController::class, 'updateOrderStatus'])->name('orders.update-status');

    // Company & Subscriptions
    Route::get('/profile', [SupplierDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [SupplierDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/subscriptions', [SupplierDashboardController::class, 'subscriptions'])->name('subscriptions');
    Route::post('/subscriptions/{id}/subscribe', [SupplierDashboardController::class, 'subscribe'])->name('subscriptions.subscribe');
});

// ==========================================
// 7. SUPER ADMIN DASHBOARD
// ==========================================
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/suppliers', [AdminDashboardController::class, 'suppliers'])->name('suppliers');
    Route::post('/suppliers/{id}/toggle-verification', [AdminDashboardController::class, 'toggleSupplierVerification'])->name('suppliers.toggle-verification');
    Route::get('/buyers', [AdminDashboardController::class, 'buyers'])->name('buyers');
    Route::get('/products', [AdminDashboardController::class, 'products'])->name('products');
    Route::post('/products/{id}/toggle-featured', [AdminDashboardController::class, 'toggleProductFeatured'])->name('products.toggle-featured');
    Route::get('/categories', [AdminDashboardController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminDashboardController::class, 'storeCategory'])->name('categories.store');
    Route::get('/orders', [AdminDashboardController::class, 'orders'])->name('orders');
    Route::get('/rfqs', [AdminDashboardController::class, 'rfqs'])->name('rfqs');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export-csv', [AdminDashboardController::class, 'exportOrdersCsv'])->name('reports.export-csv');
});

// ==========================================
// 8. LOGISTICS PARTNER DASHBOARD
// ==========================================
Route::middleware(['auth', 'role:logistics_partner,super_admin,admin'])->prefix('logistics')->name('logistics.')->group(function () {
    Route::get('/dashboard', [LogisticsDashboardController::class, 'index'])->name('dashboard');
    Route::post('/shipments/{id}/update', [LogisticsDashboardController::class, 'updateShipment'])->name('shipments.update');
});

// ==========================================
// 9. CUSTOMS PARTNER DASHBOARD (Junaid & Co.)
// ==========================================
Route::middleware(['auth', 'role:customs_partner,super_admin,admin'])->prefix('customs')->name('customs.')->group(function () {
    Route::get('/dashboard', [CustomsDashboardController::class, 'index'])->name('dashboard');
    Route::post('/clearances/{id}/update', [CustomsDashboardController::class, 'updateStatus'])->name('clearances.update');
});
