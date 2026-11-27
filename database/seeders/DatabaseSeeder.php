<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rfq;
use App\Models\Quotation;
use App\Models\Enquiry;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\CustomsClearance;
use App\Models\TrackingUpdate;
use App\Models\SubscriptionPlan;
use App\Models\SupplierSubscription;
use App\Models\HsCodeDuty;
use App\Models\Review;
use App\Models\City;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Disable foreign keys and truncate all tables to guarantee zero integrity constraint violations
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        SubscriptionPlan::truncate();
        HsCodeDuty::truncate();
        User::truncate();
        Company::truncate();
        Category::truncate();
        Product::truncate();
        Rfq::truncate();
        Quotation::truncate();
        Enquiry::truncate();
        Message::truncate();
        Order::truncate();
        OrderItem::truncate();
        Shipment::truncate();
        CustomsClearance::truncate();
        TrackingUpdate::truncate();
        SupplierSubscription::truncate();
        if (\Illuminate\Support\Facades\Schema::hasTable('cities')) {
            City::truncate();
        }
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Subscription Plans
        $freePlan = SubscriptionPlan::create([
            'name' => 'Free Supplier',
            'slug' => 'free',
            'price' => 0.00,
            'currency' => 'GBP',
            'billing_period' => 'monthly',
            'product_limit' => 10,
            'monthly_enquiry_limit' => 25,
            'featured_supplier_badge' => false,
            'priority_rfq_access' => false,
            'dedicated_account_manager' => false,
            'customs_logistics_discount' => false,
            'description' => 'Ideal for new suppliers starting their export journey.',
            'is_active' => true,
        ]);

        $basicPlan = SubscriptionPlan::create([
            'name' => 'Verified Exporter',
            'slug' => 'basic',
            'price' => 49.00,
            'currency' => 'GBP',
            'billing_period' => 'monthly',
            'product_limit' => 50,
            'monthly_enquiry_limit' => 100,
            'featured_supplier_badge' => true,
            'priority_rfq_access' => true,
            'dedicated_account_manager' => false,
            'customs_logistics_discount' => false,
            'description' => 'For active manufacturing businesses expanding into UK and European markets.',
            'is_active' => true,
        ]);

        $premiumPlan = SubscriptionPlan::create([
            'name' => 'Global Enterprise',
            'slug' => 'premium',
            'price' => 149.00,
            'currency' => 'GBP',
            'billing_period' => 'monthly',
            'product_limit' => 500,
            'monthly_enquiry_limit' => 1000,
            'featured_supplier_badge' => true,
            'priority_rfq_access' => true,
            'dedicated_account_manager' => true,
            'customs_logistics_discount' => true,
            'description' => 'Maximum visibility, priority RFQs, and integrated customs & logistics concierge.',
            'is_active' => true,
        ]);

        // 2. HS Codes & Customs Duty Rates for Landed Cost Calculator
        $duties = [
            [
                'hs_code' => '8457.10.90',
                'category_name' => 'Industrial Machinery',
                'description' => 'Machining centers for working metal, CNC milling and turning centers',
                'duty_rate_percent' => 2.70,
                'vat_rate_percent' => 20.00,
                'origin_country' => 'All',
                'destination_country' => 'United Kingdom',
                'notes' => 'Standard industrial tariff for CNC equipment imported into Great Britain',
            ],
            [
                'hs_code' => '8479.89.97',
                'category_name' => 'Industrial Machinery',
                'description' => 'Machines and mechanical appliances having individual functions',
                'duty_rate_percent' => 1.70,
                'vat_rate_percent' => 20.00,
                'origin_country' => 'All',
                'destination_country' => 'United Kingdom',
                'notes' => 'General industrial automation and mechanical appliances',
            ],
            [
                'hs_code' => '8501.52.20',
                'category_name' => 'Electrical Equipment',
                'description' => 'Multi-phase AC motors of an output exceeding 750 W but not exceeding 75 kW',
                'duty_rate_percent' => 2.50,
                'vat_rate_percent' => 20.00,
                'origin_country' => 'All',
                'destination_country' => 'United Kingdom',
                'notes' => 'Electric induction motors for industrial drives',
            ],
            [
                'hs_code' => '8483.40.22',
                'category_name' => 'Mechanical Components',
                'description' => 'Gears and gearing; ball or roller screws; gear boxes and speed changers',
                'duty_rate_percent' => 3.70,
                'vat_rate_percent' => 20.00,
                'origin_country' => 'All',
                'destination_country' => 'United Kingdom',
                'notes' => 'Planetary and helical industrial gearboxes',
            ],
            [
                'hs_code' => '7307.21.00',
                'category_name' => 'Spare Parts',
                'description' => 'Tube or pipe fittings of stainless steel - Flanges',
                'duty_rate_percent' => 3.00,
                'vat_rate_percent' => 20.00,
                'origin_country' => 'All',
                'destination_country' => 'United Kingdom',
                'notes' => 'Forged pipe flanges for oil, gas, chemical and process industries',
            ],
            [
                'hs_code' => '8481.80.79',
                'category_name' => 'Spare Parts',
                'description' => 'Taps, cocks, valves and similar appliances - Ball and plug valves',
                'duty_rate_percent' => 2.20,
                'vat_rate_percent' => 20.00,
                'origin_country' => 'All',
                'destination_country' => 'United Kingdom',
                'notes' => 'High pressure industrial fluid control valves',
            ],
            [
                'hs_code' => '8537.10.91',
                'category_name' => 'Industrial Automation',
                'description' => 'Programmable controllers for electric control or the distribution of electricity',
                'duty_rate_percent' => 0.00,
                'vat_rate_percent' => 20.00,
                'origin_country' => 'All',
                'destination_country' => 'United Kingdom',
                'notes' => 'Zero-tariff duty rate under UK Global Tariff for digital automation PLCs',
            ],
            [
                'hs_code' => '7219.21.10',
                'category_name' => 'Raw Materials',
                'description' => 'Flat-rolled products of stainless steel, hot-rolled coils and sheets',
                'duty_rate_percent' => 0.00,
                'vat_rate_percent' => 20.00,
                'origin_country' => 'All',
                'destination_country' => 'United Kingdom',
                'notes' => 'Grade 304 and 316 stainless steel raw materials',
            ],
        ];

        foreach ($duties as $duty) {
            HsCodeDuty::create($duty);
        }

        // 3. User Accounts with Exact Names Requested by User
        $password = Hash::make('password123');

        // 1. Tarun Birla (Super Admin / Executive Platform Owner)
        $superAdmin = User::create([
            'name' => 'Tarun Birla',
            'email' => 'tarun@perlcon.com',
            'phone' => '+44 20 7946 0991',
            'password' => $password,
            'role' => 'super_admin',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'London',
            'email_verified_at' => now(),
        ]);

        // Alias for easy testing
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@demo.com',
            'phone' => '+44 20 7946 0991',
            'password' => $password,
            'role' => 'super_admin',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'London',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Tarun Birla (Admin Alias)',
            'email' => 'admin@perlcon.com',
            'phone' => '+44 20 7946 0991',
            'password' => $password,
            'role' => 'super_admin',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'London',
            'email_verified_at' => now(),
        ]);

        // 2. Hariom Birla (Operations & Sourcing Director / Admin)
        $admin = User::create([
            'name' => 'Operations Admin',
            'email' => 'admin@demo.com',
            'phone' => '+44 20 7946 0992',
            'password' => $password,
            'role' => 'admin',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'London',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Hariom Birla',
            'email' => 'hariom@perlcon.com',
            'phone' => '+44 20 7946 0992',
            'password' => $password,
            'role' => 'admin',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'London',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Hariom Birla (Manager Alias)',
            'email' => 'manager@perlcon.com',
            'phone' => '+44 20 7946 0992',
            'password' => $password,
            'role' => 'admin',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'London',
            'email_verified_at' => now(),
        ]);

        // 3. Navdeep (UK Engineering Buyer / Procurement Manager)
        $buyer = User::create([
            'name' => 'Navdeep',
            'email' => 'navdeep@perlcon.com',
            'phone' => '+44 121 496 0884',
            'password' => $password,
            'role' => 'buyer',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Birmingham',
            'email_verified_at' => now(),
        ]);

        Company::create([
            'user_id' => $buyer->id,
            'company_name' => 'Navdeep Industrial UK Ltd',
            'slug' => 'navdeep-industrial-uk-ltd',
            'business_type' => 'distributor',
            'country' => 'United Kingdom',
            'country_code' => 'GB',
            'city' => 'Birmingham',
            'address' => 'Unit 14, West Midlands Industrial Park, B7 4TR',
            'tax_id' => 'GB 984 2134 50',
            'year_established' => '2008',
            'description' => 'Major British engineering and industrial turnkey contractor supplying components to manufacturing plants.',
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Navdeep (Buyer Alias)',
            'email' => 'buyer@perlcon.com',
            'phone' => '+44 121 496 0884',
            'password' => $password,
            'role' => 'buyer',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Birmingham',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Verified Buyer',
            'email' => 'buyer@demo.com',
            'phone' => '+44 121 496 0884',
            'password' => $password,
            'role' => 'buyer',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Birmingham',
            'email_verified_at' => now(),
        ]);

        // 4. Rohit (Supplier 1: India - Heavy Engineering & Forgings)
        $supplierIndia = User::create([
            'name' => 'Rohit',
            'email' => 'rohit@perlcon.com',
            'phone' => '+91 98250 12345',
            'password' => $password,
            'role' => 'supplier',
            'status' => 'active',
            'country' => 'India',
            'city' => 'Pune',
            'email_verified_at' => now(),
        ]);

        $companyIndia = Company::create([
            'user_id' => $supplierIndia->id,
            'company_name' => 'Rohit Precision Forgings Pvt Ltd',
            'slug' => 'rohit-precision-forgings-pvt-ltd',
            'logo' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=200&h=200&fit=crop',
            'banner' => 'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?w=1200&h=400&fit=crop',
            'business_type' => 'manufacturer',
            'country' => 'India',
            'country_code' => 'IN',
            'city' => 'Pune',
            'state' => 'Maharashtra',
            'address' => 'Plot 42-B, MIDC Industrial Area, Bhosari, Pune 411026',
            'postal_code' => '411026',
            'tax_id' => '27AAACB1234F1Z5 (GSTIN)',
            'year_established' => '1998',
            'employees_count' => '450+ Engineers & Technicians',
            'annual_turnover' => '$25M - $50M USD',
            'export_markets' => 'UK, Germany, France, UAE, USA',
            'description' => 'Premier ISO 9001:2015 and CE certified heavy engineering manufacturer specializing in forged industrial flanges, CNC turned mechanical components, high-torque gear assemblies, and cast steel valves.',
            'manufacturing_capabilities' => '40 CNC Turning Lathes, 15 Vertical Machining Centers (VMC), 5000-ton hydraulic forging press, spectrometer material testing lab, CMM quality inspection.',
            'certifications' => ['ISO 9001:2015', 'CE Certification', 'RoHS Compliant', 'TUV Nord Certified', 'PED 2014/68/EU'],
            'website' => 'https://rohit-forgings.com',
            'is_verified' => true,
            'rating' => 4.9,
            'reviews_count' => 38,
        ]);

        SupplierSubscription::create([
            'supplier_id' => $supplierIndia->id,
            'plan_id' => $premiumPlan->id,
            'starts_at' => now()->subMonths(2),
            'expires_at' => now()->addMonths(10),
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Rohit (Supplier India Alias)',
            'email' => 'supplier.india@perlcon.com',
            'phone' => '+91 98250 12345',
            'password' => $password,
            'role' => 'supplier',
            'status' => 'active',
            'country' => 'India',
            'city' => 'Pune',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Rohit Precision Forgings (Supplier)',
            'email' => 'supplier@demo.com',
            'phone' => '+91 98250 12345',
            'password' => $password,
            'role' => 'supplier',
            'status' => 'active',
            'country' => 'India',
            'city' => 'Pune',
            'email_verified_at' => now(),
        ]);

        // 5. Tarry (Supplier 2: China - CNC Machinery & Industrial Automation)
        $supplierChina = User::create([
            'name' => 'Tarry',
            'email' => 'tarry@perlcon.com',
            'phone' => '+86 755 8345 6789',
            'password' => $password,
            'role' => 'supplier',
            'status' => 'active',
            'country' => 'China',
            'city' => 'Shenzhen',
            'email_verified_at' => now(),
        ]);

        $companyChina = Company::create([
            'user_id' => $supplierChina->id,
            'company_name' => 'Tarry Automation & CNC Systems Ltd',
            'slug' => 'tarry-automation-cnc-systems',
            'logo' => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?w=200&h=200&fit=crop',
            'banner' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1200&h=400&fit=crop',
            'business_type' => 'manufacturer',
            'country' => 'China',
            'country_code' => 'CN',
            'city' => 'Shenzhen',
            'state' => 'Guangdong',
            'address' => 'Baoan High-Tech Industrial Park, Shenzhen 518101',
            'postal_code' => '518101',
            'tax_id' => '91440300MA5EXXX99',
            'year_established' => '2006',
            'employees_count' => '600+ Staff',
            'annual_turnover' => '$40M - $80M USD',
            'export_markets' => 'UK, Europe, North America, Southeast Asia',
            'description' => 'Leading producer of 5-Axis CNC machining centers, high-speed automated robotic pick-and-place arms, programmable logic controllers (PLC), and industrial servo drive units.',
            'manufacturing_capabilities' => 'Modern 30,000 sqm digitalized factory with robotic assembly lines, high-precision laser interferometers, automated clean-room electronic assembly.',
            'certifications' => ['ISO 9001', 'ISO 14001', 'CE Machinery Directive', 'FCC', 'UL'],
            'website' => 'https://tarry-automation.com',
            'is_verified' => true,
            'rating' => 4.8,
            'reviews_count' => 52,
        ]);

        SupplierSubscription::create([
            'supplier_id' => $supplierChina->id,
            'plan_id' => $premiumPlan->id,
            'starts_at' => now()->subMonths(3),
            'expires_at' => now()->addMonths(9),
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Tarry (Supplier China Alias)',
            'email' => 'supplier.china@perlcon.com',
            'phone' => '+86 755 8345 6789',
            'password' => $password,
            'role' => 'supplier',
            'status' => 'active',
            'country' => 'China',
            'city' => 'Shenzhen',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Tarry CNC Systems (Supplier 2)',
            'email' => 'supplier2@demo.com',
            'phone' => '+86 755 8345 6789',
            'password' => $password,
            'role' => 'supplier',
            'status' => 'active',
            'country' => 'China',
            'city' => 'Shenzhen',
            'email_verified_at' => now(),
        ]);

        // 6. Harry (Logistics Partner - Air, Sea, & UK Intermodal Freight)
        $logisticsUser = User::create([
            'name' => 'Harry',
            'email' => 'harry@perlcon.com',
            'phone' => '+44 20 8759 2300',
            'password' => $password,
            'role' => 'logistics_partner',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Heathrow',
            'email_verified_at' => now(),
        ]);

        Company::create([
            'user_id' => $logisticsUser->id,
            'company_name' => 'Harry Global Express & Freight Forwarding Ltd',
            'slug' => 'harry-global-freight-forwarding',
            'business_type' => 'distributor',
            'country' => 'United Kingdom',
            'country_code' => 'GB',
            'city' => 'Heathrow',
            'address' => 'Cargo Terminal 4, Heathrow Airport, London TW6 3XA',
            'tax_id' => 'GB 671 9021 11',
            'year_established' => '2001',
            'description' => 'Specialist international air & sea freight forwarder handling containerized LCL/FCL shipments and direct courier delivery across the UK.',
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Harry (Logistics Alias)',
            'email' => 'logistics@perlcon.com',
            'phone' => '+44 20 8759 2300',
            'password' => $password,
            'role' => 'logistics_partner',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Heathrow',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Harry Global Logistics',
            'email' => 'logistics@demo.com',
            'phone' => '+44 20 8759 2300',
            'password' => $password,
            'role' => 'logistics_partner',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Heathrow',
            'email_verified_at' => now(),
        ]);

        // 7. Nasar (Customs Clearance Partner - C88 & HMRC CDS Specialist)
        $customsUser = User::create([
            'name' => 'Nasar',
            'email' => 'nasar@perlcon.com',
            'phone' => '+44 1394 670 120',
            'password' => $password,
            'role' => 'customs_partner',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Felixstowe',
            'email_verified_at' => now(),
        ]);

        Company::create([
            'user_id' => $customsUser->id,
            'company_name' => 'Nasar & Co. UK Customs Clearance Specialists',
            'slug' => 'nasar-customs-clearance-specialists',
            'business_type' => 'distributor',
            'country' => 'United Kingdom',
            'country_code' => 'GB',
            'city' => 'Felixstowe',
            'address' => 'Dock Head House, Port of Felixstowe, IP11 3SY',
            'tax_id' => 'GB 512 8844 77',
            'year_established' => '2010',
            'description' => 'Authorised Economic Operator (AEO-F) accredited customs broker providing direct HMRC CDS customs clearances, C88 processing, duty deferment handling, and import VAT optimization.',
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Nasar (Customs Alias)',
            'email' => 'customs@perlcon.com',
            'phone' => '+44 1394 670 120',
            'password' => $password,
            'role' => 'customs_partner',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Felixstowe',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Junaid & Co. Customs Clearance',
            'email' => 'customs@demo.com',
            'phone' => '+44 1394 670 120',
            'password' => $password,
            'role' => 'customs_partner',
            'status' => 'active',
            'country' => 'United Kingdom',
            'city' => 'Felixstowe',
            'email_verified_at' => now(),
        ]);

        // 4. Product Categories & Subcategories (5 Standard Core Categories with images & 4 subcategories each)
        $categoriesData = [
            [
                'name' => 'Industrial Machinery & Equipment',
                'slug' => 'industrial-machinery',
                'icon' => 'fa-cogs',
                'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=600&h=450&fit=crop',
                'description' => 'CNC machines, hydraulic presses, lathe machines, and automated manufacturing equipment.',
                'is_featured' => true,
                'show_on_home' => true,
                'subs' => [
                    ['name' => 'CNC Machines', 'image' => 'https://images.unsplash.com/photo-1616401784845-180882ba9ba8?w=400&h=400&fit=crop'],
                    ['name' => 'Hydraulic Presses', 'image' => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?w=400&h=400&fit=crop'],
                    ['name' => 'Lathe & Milling Tools', 'image' => 'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?w=400&h=400&fit=crop'],
                    ['name' => 'Robotics & Automation', 'image' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=400&h=400&fit=crop'],
                ],
            ],
            [
                'name' => 'Building & Construction Material',
                'slug' => 'building-construction',
                'icon' => 'fa-building',
                'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186156f?w=600&h=450&fit=crop',
                'description' => 'TMT steel bars, industrial cement, structural beams, and construction hardware.',
                'is_featured' => true,
                'show_on_home' => true,
                'subs' => [
                    ['name' => 'TMT Steel Bars', 'image' => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=400&h=400&fit=crop'],
                    ['name' => 'Industrial Cement', 'image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=400&h=400&fit=crop'],
                    ['name' => 'Structural Steel Pipes', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=400&h=400&fit=crop'],
                    ['name' => 'Scaffolding & Formwork', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=400&h=400&fit=crop'],
                ],
            ],
            [
                'name' => 'Electronics & Electrical Goods',
                'slug' => 'electronics-electrical',
                'icon' => 'fa-bolt',
                'image' => 'https://images.unsplash.com/photo-1509391365360-2e959784a276?w=600&h=450&fit=crop',
                'description' => 'Solar panels, 3-phase electric motors, power transformers, and switchgear equipment.',
                'is_featured' => true,
                'show_on_home' => true,
                'subs' => [
                    ['name' => 'Solar Energy Panels', 'image' => 'https://images.unsplash.com/photo-1508873696983-2df5293cb32f?w=400&h=400&fit=crop'],
                    ['name' => '3-Phase Electric Motors', 'image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?w=400&h=400&fit=crop'],
                    ['name' => 'Power Transformers', 'image' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=400&h=400&fit=crop'],
                    ['name' => 'Switchgear & Relays', 'image' => 'https://images.unsplash.com/photo-1555664424-778a1e5e1b48?w=400&h=400&fit=crop'],
                ],
            ],
            [
                'name' => 'Packaging & Paper Supplies',
                'slug' => 'packaging-paper',
                'icon' => 'fa-box-open',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&h=450&fit=crop',
                'description' => 'Corrugated shipping boxes, stretch film rolls, packing tapes, and warehouse supplies.',
                'is_featured' => true,
                'show_on_home' => true,
                'subs' => [
                    ['name' => 'Corrugated Shipping Boxes', 'image' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?w=400&h=400&fit=crop'],
                    ['name' => 'Packaging Film & Tape', 'image' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=400&h=400&fit=crop'],
                    ['name' => 'Storage Crates & Pallets', 'image' => 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?w=400&h=400&fit=crop'],
                    ['name' => 'Industrial Strapping Tape', 'image' => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?w=400&h=400&fit=crop'],
                ],
            ],
            [
                'name' => 'Chemicals, Dyes & Solvents',
                'slug' => 'chemicals-dyes',
                'icon' => 'fa-flask',
                'image' => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=600&h=450&fit=crop',
                'description' => 'Industrial grade solvents, pure acetone, organic dyes, and surface cleaning chemicals.',
                'is_featured' => true,
                'show_on_home' => true,
                'subs' => [
                    ['name' => 'Industrial Solvents', 'image' => 'https://images.unsplash.com/photo-1603555501671-8f96b3fce8b5?w=400&h=400&fit=crop'],
                    ['name' => 'Organic Pigments & Dyes', 'image' => 'https://images.unsplash.com/photo-1507668077129-56e32842fceb?w=400&h=400&fit=crop'],
                    ['name' => 'Water Treatment Chemicals', 'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&h=400&fit=crop'],
                    ['name' => 'Polymer Resins', 'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=400&h=400&fit=crop'],
                ],
            ],
        ];

        $categoryMap = [];
        $order = 1;
        foreach ($categoriesData as $catData) {
            $parent = Category::create([
                'name' => $catData['name'],
                'slug' => $catData['slug'],
                'icon' => $catData['icon'],
                'image' => $catData['image'],
                'description' => $catData['description'],
                'is_featured' => $catData['is_featured'],
                'show_on_home' => $catData['show_on_home'],
                'sort_order' => $order++,
            ]);
            $categoryMap[$catData['slug']] = $parent;

            foreach ($catData['subs'] as $subItem) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $subItem['name'],
                    'slug' => \Illuminate\Support\Str::slug($subItem['name']),
                    'icon' => $catData['icon'],
                    'image' => $subItem['image'],
                    'description' => "Subcategory for {$subItem['name']}",
                    'is_featured' => false,
                    'show_on_home' => true,
                    'sort_order' => $order++,
                ]);
            }
        }

        // 4.1 Seed 10 Global Industrial Cities
        $citiesData = [
            ['name' => 'Pune', 'slug' => 'pune', 'country' => 'India', 'image' => 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?w=600&h=450&fit=crop', 'sort_order' => 1],
            ['name' => 'Shenzhen', 'slug' => 'shenzhen', 'country' => 'China', 'image' => 'https://images.unsplash.com/photo-1508873696983-2df5293cb32f?w=600&h=450&fit=crop', 'sort_order' => 2],
            ['name' => 'London', 'slug' => 'london', 'country' => 'United Kingdom', 'image' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=600&h=450&fit=crop', 'sort_order' => 3],
            ['name' => 'Birmingham', 'slug' => 'birmingham', 'country' => 'United Kingdom', 'image' => 'https://images.unsplash.com/photo-1596701062351-8c2c14d1fdd0?w=600&h=450&fit=crop', 'sort_order' => 4],
            ['name' => 'Shanghai', 'slug' => 'shanghai', 'country' => 'China', 'image' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?w=600&h=450&fit=crop', 'sort_order' => 5],
            ['name' => 'Stuttgart', 'slug' => 'stuttgart', 'country' => 'Germany', 'image' => 'https://images.unsplash.com/photo-1582233479366-6d38bc390a08?w=600&h=450&fit=crop', 'sort_order' => 6],
            ['name' => 'Ahmedabad', 'slug' => 'ahmedabad', 'country' => 'India', 'image' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?w=600&h=450&fit=crop', 'sort_order' => 7],
            ['name' => 'Manchester', 'slug' => 'manchester', 'country' => 'United Kingdom', 'image' => 'https://images.unsplash.com/photo-1579282240050-352db0a14c21?w=600&h=450&fit=crop', 'sort_order' => 8],
            ['name' => 'Munich', 'slug' => 'munich', 'country' => 'Germany', 'image' => 'https://images.unsplash.com/photo-1595867818082-083862f3d630?w=600&h=450&fit=crop', 'sort_order' => 9],
            ['name' => 'Mumbai', 'slug' => 'mumbai', 'country' => 'India', 'image' => 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?w=600&h=450&fit=crop', 'sort_order' => 10],
        ];

        foreach ($citiesData as $c) {
            City::create([
                'name' => $c['name'],
                'slug' => $c['slug'],
                'country' => $c['country'],
                'image' => $c['image'],
                'status' => 'active',
                'sort_order' => $c['sort_order'],
                'show_on_home' => true,
            ]);
        }

        // 5. Exactly 5 Clean, Real Products matching the 5 Core Categories
        $productsData = [
            // 1. Industrial Machinery & Equipment
            [
                'supplier_id' => $supplierIndia->id,
                'company_id' => $companyIndia->id,
                'category_id' => $categoryMap['industrial-machinery']->id,
                'name' => 'Hydraulic CNC Press Brake Machine (100 Ton)',
                'slug' => 'hydraulic-cnc-press-brake-machine-100-ton',
                'sku' => 'MRT-MCH-01',
                'hs_code' => '8462.29.00',
                'short_description' => 'Heavy-duty 100-ton hydraulic sheet metal bending press brake machine with multi-axis CNC digital controller.',
                'full_description' => 'Professional industrial CNC press brake machine featuring 100-ton bending force, 3200mm bending length, high-accuracy electro-hydraulic servo synchronization, and user-friendly touch screen control. Ideal for sheet metal fabrication, automotive body panels, and structural engineering.',
                'technical_specifications' => [
                    'Bending Force' => '1000 kN (100 Tons)',
                    'Bending Length' => '3200 mm',
                    'Distance Between Columns' => '2600 mm',
                    'Throat Depth' => '320 mm',
                    'CNC Controller' => 'Delem DA-53T Multi-Axis Touchscreen',
                    'Motor Power' => '7.5 kW 3-Phase',
                    'Certifications' => 'CE, ISO 9001:2015',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&q=80',
                'currency' => 'GBP',
                'base_price' => 4500.00,
                'moq' => 1,
                'unit' => 'Pieces',
                'weight_kg' => 6500.0,
                'dimensions' => '3600 x 1700 x 2400 mm',
                'lead_time_days' => 20,
                'manufacturing_capacity' => '25 Units / Month',
                'origin_country' => 'India',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 12,
            ],
            // 2. Building & Construction Material
            [
                'supplier_id' => $supplierIndia->id,
                'company_id' => $companyIndia->id,
                'category_id' => $categoryMap['building-construction']->id,
                'name' => 'Fe 550D High Strength TMT Steel Rebars (12mm)',
                'slug' => 'fe-550d-high-strength-tmt-steel-rebars-12mm',
                'sku' => 'MRT-BLD-02',
                'hs_code' => '7214.20.00',
                'short_description' => 'Earthquake resistant Fe 550D thermo-mechanically treated reinforcement steel bars for construction and infrastructure.',
                'full_description' => 'Premium Grade Fe 550D TMT steel reinforcement rebars engineered with superior ductility, high yield strength (minimum 550 N/mm²), and excellent weldability. Suitable for high-rise buildings, bridges, highways, and heavy industrial foundations.',
                'technical_specifications' => [
                    'Diameter' => '12 mm (Available 8mm to 32mm)',
                    'Steel Grade' => 'Fe 550D (IS 1786 / BS 4449)',
                    'Yield Strength' => 'Min 550 N/mm²',
                    'Tensile Strength' => 'Min 600 N/mm²',
                    'Elongation' => 'Min 14.5%',
                    'Corrosion Resistance' => 'High Corrosion Resistant (CRS)',
                    'Standard Length' => '12 Meters / Bar',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?w=800&q=80',
                'currency' => 'GBP',
                'base_price' => 650.00,
                'moq' => 5,
                'unit' => 'Metric Tons',
                'weight_kg' => 1000.0,
                'dimensions' => '12000 x 12 x 12 mm',
                'lead_time_days' => 7,
                'manufacturing_capacity' => '5000 Metric Tons / Month',
                'origin_country' => 'India',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 250,
            ],
            // 3. Electronics & Electrical Goods
            [
                'supplier_id' => $supplierChina->id,
                'company_id' => $companyChina->id,
                'category_id' => $categoryMap['electronics-electrical']->id,
                'name' => '550W Monocrystalline Bifacial Solar Panel (Tier-1)',
                'slug' => '550w-monocrystalline-bifacial-solar-panel-tier-1',
                'sku' => 'MRT-ELE-03',
                'hs_code' => '8541.40.00',
                'short_description' => 'High efficiency 21.5% half-cut cell bifacial solar PV module with 25-year linear power warranty.',
                'full_description' => 'Tier-1 certified 550W monocrystalline dual-glass bifacial solar panel designed for commercial rooftop installations and ground-mounted solar farms. Features multi-busbar (MBB) half-cut cells delivering up to 25% additional rear-side power gain in reflective environments.',
                'technical_specifications' => [
                    'Rated Power (Pmax)' => '550 Watts',
                    'Module Efficiency' => '21.5%',
                    'Cell Type' => 'Monocrystalline Half-Cell (144 Cells)',
                    'Open Circuit Voltage (Voc)' => '49.8 V',
                    'Short Circuit Current (Isc)' => '14.05 A',
                    'Glass' => 'Dual 2.0mm Anti-Reflective Tempered Glass',
                    'Warranty' => '12 Years Product, 25 Years Performance',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1509391365360-2e959784a276?w=800&q=80',
                'currency' => 'GBP',
                'base_price' => 110.00,
                'moq' => 10,
                'unit' => 'Pieces',
                'weight_kg' => 28.5,
                'dimensions' => '2278 x 1134 x 35 mm',
                'lead_time_days' => 14,
                'manufacturing_capacity' => '20,000 Panels / Month',
                'origin_country' => 'China',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 1200,
            ],
            // 4. Packaging & Paper Supplies
            [
                'supplier_id' => $supplierIndia->id,
                'company_id' => $companyIndia->id,
                'category_id' => $categoryMap['packaging-paper']->id,
                'name' => 'Heavy Duty 5-Ply Corrugated Shipping Boxes (Pack of 50)',
                'slug' => 'heavy-duty-5-ply-corrugated-shipping-boxes-pack-of-50',
                'sku' => 'MRT-PKG-04',
                'hs_code' => '4819.10.00',
                'short_description' => 'Heavy duty double wall 5-ply corrugated cardboard shipping boxes for export packaging and logistics.',
                'full_description' => 'Industrial grade double-wall 5-ply corrugated cardboard carton boxes designed to withstand heavy freight stacking and rough transit handling. Manufactured from 100% recyclable high-burst test kraft liner paper. Delivered flat packed in bundles of 50 pieces.',
                'technical_specifications' => [
                    'Ply Type' => '5-Ply Double Wall (AB-Flute)',
                    'Internal Dimensions' => '600 x 400 x 400 mm (L x W x H)',
                    'Bursting Strength' => '14 kg/cm² (200 PSI)',
                    'Load Capacity' => 'Up to 35 kg per box',
                    'Material' => '100% Virgin Kraft Paper (Recyclable)',
                    'Packing Unit' => 'Bundle of 50 Boxes',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80',
                'currency' => 'GBP',
                'base_price' => 45.00,
                'moq' => 5,
                'unit' => 'Sets',
                'weight_kg' => 25.0,
                'dimensions' => '600 x 400 x 400 mm',
                'lead_time_days' => 5,
                'manufacturing_capacity' => '100,000 Boxes / Month',
                'origin_country' => 'United Kingdom',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 350,
            ],
            // 5. Chemicals, Dyes & Solvents
            [
                'supplier_id' => $supplierChina->id,
                'company_id' => $companyChina->id,
                'category_id' => $categoryMap['chemicals-dyes']->id,
                'name' => 'Industrial Grade Pure Acetone Solvent (200L Drum)',
                'slug' => 'industrial-grade-pure-acetone-solvent-200l-drum',
                'sku' => 'MRT-CHM-05',
                'hs_code' => '2914.11.00',
                'short_description' => '99.5% minimum purity technical grade acetone solvent in UN-approved steel drums for manufacturing & degreasing.',
                'full_description' => 'High purity clear liquid acetone solvent widely used across pharmaceutical synthesis, fibreglass manufacturing, epoxy resin thinning, and industrial metal surface degreasing. Packed in heavy duty 200 litre UN certified steel drums with tamper-proof seal.',
                'technical_specifications' => [
                    'Purity' => 'Min 99.5% wt.',
                    'Appearance' => 'Clear, Colorless Liquid',
                    'Boiling Point' => '56.1 °C',
                    'Water Content' => 'Max 0.3%',
                    'Packaging' => '200 Litre UN Steel Drum',
                    'Hazard Classification' => 'Class 3 Flammable Liquid (UN 1090)',
                    'Safety Data Sheet' => 'Compliant with UK REACH / EU REACH',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=800&q=80',
                'currency' => 'GBP',
                'base_price' => 175.00,
                'moq' => 2,
                'unit' => 'Pieces',
                'weight_kg' => 170.0,
                'dimensions' => '580 x 580 x 880 mm',
                'lead_time_days' => 10,
                'manufacturing_capacity' => '500 Drums / Month',
                'origin_country' => 'Germany',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 80,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $pData) {
            $createdProducts[] = Product::create($pData);
        }

        // 6. Realistic RFQs from Buyer
        $rfq1 = Rfq::create([
            'rfq_number' => 'RFQ-2025-001',
            'buyer_id' => $buyer->id,
            'category_id' => $categoryMap['industrial-machinery']->id,
            'product_name' => '500 Pcs ASME B16.5 Class 300 SS316L Forged Flanges (6 Inch DN150)',
            'quantity' => 500,
            'unit' => 'Pieces',
            'target_price' => 35.00,
            'currency' => 'GBP',
            'preferred_countries' => ['India', 'China'],
            'required_delivery_date' => now()->addDays(40),
            'technical_specifications' => 'Must comply with ASME B16.5 Raised Face (RF), 3.1 Material Test Certificate, 100% Ultrasonic Testing, NACE MR0175 compliance for North Sea offshore gas terminal.',
            'additional_requirements' => 'Deliver CIF Felixstowe Port with full commercial invoice, packing list, and certificate of origin.',
            'destination_country' => 'United Kingdom',
            'destination_port_city' => 'Port of Felixstowe',
            'status' => 'quoted',
        ]);

        $rfq2 = Rfq::create([
            'rfq_number' => 'RFQ-2025-002',
            'buyer_id' => $buyer->id,
            'category_id' => $categoryMap['electronics-electrical']->id,
            'product_name' => '15 Units 3-Phase IE3 Premium Efficiency Electric Motors (45kW, 400V 50Hz)',
            'quantity' => 15,
            'unit' => 'Units',
            'target_price' => 680.00,
            'currency' => 'GBP',
            'preferred_countries' => ['India', 'Germany'],
            'required_delivery_date' => now()->addDays(30),
            'technical_specifications' => '45kW, 1480 RPM, IP55 protection, B3 Foot Mounted, PTC thermistors embedded in windings for temperature monitoring.',
            'additional_requirements' => 'Air Freight delivery to London Heathrow Cargo Terminal.',
            'destination_country' => 'United Kingdom',
            'destination_port_city' => 'Heathrow Airport',
            'status' => 'open',
        ]);

        // 7. Quotations submitted by Suppliers for RFQ-2025-001
        // Quote 1: From India Supplier (Accepted)
        $quoteIndia = Quotation::create([
            'quotation_number' => 'QTN-2025-001-IND',
            'rfq_id' => $rfq1->id,
            'product_id' => $createdProducts[0]->id,
            'supplier_id' => $supplierIndia->id,
            'buyer_id' => $buyer->id,
            'unit_price' => 32.50,
            'quantity' => 500,
            'subtotal_amount' => 16250.00,
            'shipping_cost' => 1100.00,
            'insurance_cost' => 150.00,
            'estimated_customs_duty' => 487.50, // 3%
            'estimated_vat' => 3597.50, // 20%
            'total_landed_cost' => 21585.00,
            'currency' => 'GBP',
            'shipping_method' => 'Consolidated Sea Freight (LCL)',
            'estimated_delivery_days' => 24,
            'validity_date' => now()->addDays(30),
            'payment_terms' => '30% Advance deposit, 70% against Bill of Lading copy',
            'notes' => 'Includes 100% NDT inspection, wooden pallet export packaging, and certificate of origin.',
            'status' => 'accepted',
        ]);

        // Quote 2: From China Supplier (Competing quote for comparison)
        $quoteChina = Quotation::create([
            'quotation_number' => 'QTN-2025-001-CHN',
            'rfq_id' => $rfq1->id,
            'product_id' => null,
            'supplier_id' => $supplierChina->id,
            'buyer_id' => $buyer->id,
            'unit_price' => 34.00,
            'quantity' => 500,
            'subtotal_amount' => 17000.00,
            'shipping_cost' => 1350.00,
            'insurance_cost' => 180.00,
            'estimated_customs_duty' => 510.00,
            'estimated_vat' => 3808.00,
            'total_landed_cost' => 22848.00,
            'currency' => 'GBP',
            'shipping_method' => 'Consolidated Sea Freight (LCL)',
            'estimated_delivery_days' => 28,
            'validity_date' => now()->addDays(25),
            'payment_terms' => 'Letter of Credit (L/C) at sight or T/T',
            'notes' => 'Export standard seaworthy packaging with desiccant protection.',
            'status' => 'sent',
        ]);

        // 8. Enquiries
        $enquiry1 = Enquiry::create([
            'enquiry_number' => 'ENQ-2025-001',
            'product_id' => $createdProducts[3]->id, // CNC VMC850
            'supplier_id' => $supplierChina->id,
            'buyer_id' => $buyer->id,
            'buyer_name' => 'David Sterling',
            'buyer_email' => 'buyer@perlcon.com',
            'buyer_phone' => '+44 121 496 0884',
            'buyer_company' => 'Apex British Industrial Contracting Ltd',
            'buyer_country' => 'United Kingdom',
            'subject' => 'Request for Optical Scale Options & UK Commissioning for VMC-850',
            'message' => 'Hello Wei Chen, we are interested in ordering 2 sets of the 5-Axis VMC-850. Can you confirm if Heidenhain glass scales are pre-installed on all 5 axes, and whether Perlcon/partner engineers can perform onsite commissioning in Birmingham?',
            'required_quantity' => 2,
            'unit' => 'Sets',
            'status' => 'in_discussion',
        ]);

        // 9. Internal Messages
        Message::create([
            'sender_id' => $buyer->id,
            'receiver_id' => $supplierChina->id,
            'enquiry_id' => $enquiry1->id,
            'message' => 'Hello Tarry, we are interested in ordering 2 sets of the 5-Axis VMC-850. Can you confirm if Heidenhain glass scales are pre-installed on all 5 axes?',
            'is_read' => true,
            'read_at' => now()->subHours(5),
            'created_at' => now()->subHours(6),
        ]);

        Message::create([
            'sender_id' => $supplierChina->id,
            'receiver_id' => $buyer->id,
            'enquiry_id' => $enquiry1->id,
            'message' => 'Dear Navdeep, yes, both machines come factory fitted with German Heidenhain LC485 linear optical encoders. Furthermore, Perlcon\'s UK engineering team can handle the on-site calibration and CE conformity certificate in Birmingham.',
            'is_read' => true,
            'read_at' => now()->subHours(2),
            'created_at' => now()->subHours(3),
        ]);

        // 10. Completed Multi-Supplier Order demonstrating split purchase orders and 14-stage tracking!
        $order = Order::create([
            'order_number' => 'ORD-2025-8821',
            'buyer_id' => $buyer->id,
            'quotation_id' => $quoteIndia->id,
            'subtotal_amount' => 17810.00, // Flanges from India + Electric Motor from India + PLC from China
            'shipping_amount' => 1450.00,
            'customs_duty_amount' => 534.30,
            'vat_amount' => 3958.86,
            'discount_amount' => 250.00,
            'total_amount' => 23503.16,
            'currency' => 'GBP',
            'payment_status' => 'paid',
            'payment_method' => 'stripe',
            'payment_reference' => 'ch_3N4jK82eZvKYlo2C09qPzV88',
            'order_status' => 'in_transit', // Demonstrates tracking stage 7
            'shipping_address' => [
                'recipient_name' => 'Navdeep - Navdeep Industrial UK Ltd',
                'company_name' => 'Navdeep Industrial UK Ltd',
                'address_line_1' => 'Unit 14, West Midlands Industrial Park',
                'address_line_2' => 'Aston Expressway Distribution Hub',
                'city' => 'Birmingham',
                'postal_code' => 'B7 4TR',
                'country' => 'United Kingdom',
                'contact_phone' => '+44 121 496 0884',
            ],
            'billing_address' => [
                'company_name' => 'Navdeep Industrial UK Ltd',
                'vat_number' => 'GB 984 2134 50',
                'address' => 'Unit 14, West Midlands Industrial Park, Birmingham B7 4TR',
            ],
            'destination_country' => 'United Kingdom',
            'tracking_number' => 'PRL-UK-2025-8821',
            'notes' => 'High-priority delivery for plant retrofit project. Please coordinate with Nasar & Co. for fast-track customs clearance at Port of Felixstowe.',
        ]);

        // Split Purchase Order Item 1 (Supplier India: Flanges)
        OrderItem::create([
            'order_id' => $order->id,
            'purchase_order_number' => 'PO-8821-IND-01',
            'supplier_id' => $supplierIndia->id,
            'product_id' => $createdProducts[0]->id,
            'product_name' => 'ASME B16.5 Class 300 Stainless Steel 316L Forged Flanges',
            'sku' => 'IND-FLG-316L-01',
            'hs_code' => '7307.21.00',
            'unit_price' => 32.50,
            'quantity' => 500,
            'unit' => 'Pieces',
            'subtotal' => 16250.00,
            'supplier_shipping_share' => 1100.00,
            'supplier_status' => 'dispatched',
            'supplier_tracking_number' => 'IN-NSA-FELIX-9082',
            'supplier_notes' => 'Dispatched from Nhava Sheva (JNPT) container terminal via MSC container liner.',
        ]);

        // Split Purchase Order Item 2 (Supplier India: Electric Motor)
        OrderItem::create([
            'order_id' => $order->id,
            'purchase_order_number' => 'PO-8821-IND-02',
            'supplier_id' => $supplierIndia->id,
            'product_id' => $createdProducts[1]->id,
            'product_name' => 'High-Efficiency IE3 30kW 3-Phase Induction Motor',
            'sku' => 'IND-MOT-IE3-30KW',
            'hs_code' => '8501.52.20',
            'unit_price' => 780.00,
            'quantity' => 1,
            'unit' => 'Units',
            'subtotal' => 780.00,
            'supplier_shipping_share' => 150.00,
            'supplier_status' => 'dispatched',
            'supplier_tracking_number' => 'IN-NSA-FELIX-9082',
        ]);

        // Split Purchase Order Item 3 (Supplier China: Modular PLC)
        OrderItem::create([
            'order_id' => $order->id,
            'purchase_order_number' => 'PO-8821-CHN-01',
            'supplier_id' => $supplierChina->id,
            'product_id' => $createdProducts[2]->id,
            'product_name' => 'Modular Industrial PLC Controller CPU with Modbus TCP',
            'sku' => 'CHN-PLC-MOD-32IO',
            'hs_code' => '8537.10.91',
            'unit_price' => 260.00,
            'quantity' => 3,
            'unit' => 'Units',
            'subtotal' => 780.00,
            'supplier_shipping_share' => 200.00,
            'supplier_status' => 'dispatched',
            'supplier_tracking_number' => 'DHL-774910284',
            'supplier_notes' => 'Dispatched via DHL Express directly to Perlcon consolidation warehouse.',
        ]);

        // 11. Shipment records for the Order
        $shipment = Shipment::create([
            'shipment_number' => 'SHP-2025-0914',
            'order_id' => $order->id,
            'logistics_partner_id' => $logisticsUser->id,
            'carrier_name' => 'Consolidated Sea Freight (LCL) / MSC Maritime',
            'shipment_type' => 'sea_lcl',
            'tracking_number' => 'MSC-UUK-9921477',
            'origin_country' => 'India',
            'origin_port_city' => 'Nhava Sheva Port (JNPT), Mumbai',
            'destination_country' => 'United Kingdom',
            'destination_port_city' => 'Port of Felixstowe / London Gateway',
            'total_weight_kg' => 2595.00,
            'volume_cbm' => 4.250,
            'packages_count' => 6,
            'estimated_dispatch_date' => now()->subDays(10),
            'estimated_delivery_date' => now()->addDays(14),
            'current_location' => 'Vessel Mediterranean Highway - Approaching Red Sea / Suez Transit',
            'status' => 'in_transit',
            'shipping_documents' => [
                'bill_of_lading' => 'BL-MSC-9921477.pdf',
                'packing_list' => 'PL-8821-EXPORT.pdf',
                'commercial_invoice' => 'INV-8821-UK.pdf',
            ],
        ]);

        // 12. Customs Clearance Record with Nasar & Co.
        CustomsClearance::create([
            'clearance_reference' => 'CUS-UK-2025-4491',
            'order_id' => $order->id,
            'shipment_id' => $shipment->id,
            'customs_partner_id' => $customsUser->id,
            'port_of_entry' => 'Port of Felixstowe',
            'country_of_origin' => 'India',
            'hs_codes' => '7307.21.00, 8501.52.20, 8537.10.91',
            'declared_cif_value' => 19260.00,
            'applicable_duty_rate' => 2.77,
            'duty_payable_amount' => 534.30,
            'vat_rate' => 20.00,
            'vat_payable_amount' => 3958.86,
            'clearance_service_fee' => 120.00,
            'total_customs_charges' => 4613.16,
            'currency' => 'GBP',
            'status' => 'declaration_filed',
            'c88_entry_number' => 'CDS-25-GB-098241',
            'partner_notes' => 'Pre-lodged in HMRC CDS system. Commercial invoice and origin declarations verified under UK-India Trade Facilitation regulations.',
        ]);

        // 13. 14-Stage Visual Order Tracking Timeline
        $timelineUpdates = [
            [
                'stage' => 'order_placed',
                'title' => 'Order Placed & Confirmed by Buyer',
                'description' => 'Purchase order ORD-2025-8821 confirmed via Perlcon B2B Gateway.',
                'location' => 'London, United Kingdom',
                'occurred_at' => now()->subDays(12),
            ],
            [
                'stage' => 'payment_confirmed',
                'title' => 'Escrow Payment Confirmed',
                'description' => 'Payment of £23,503.16 secured in Perlcon B2B Trade Assurance Escrow.',
                'location' => 'Stripe UK Escrow Gateway',
                'occurred_at' => now()->subDays(12)->addHours(2),
            ],
            [
                'stage' => 'supplier_confirmed',
                'title' => 'Suppliers Confirmed Production & Stock',
                'description' => 'Bharat Heavy Forge (India) and Shenzhen Precision (China) confirmed production schedule.',
                'location' => 'Pune (India) & Shenzhen (China)',
                'occurred_at' => now()->subDays(11),
            ],
            [
                'stage' => 'processing',
                'title' => 'Factory Quality Inspection & Packing',
                'description' => '100% PMI and ultrasonic testing completed. Seaworthy pallet packaging labeled with UK destination marks.',
                'location' => 'Manufacturing Facilities',
                'occurred_at' => now()->subDays(10),
            ],
            [
                'stage' => 'ready_for_dispatch',
                'title' => 'Consolidated at Origin Port Cargo Terminal',
                'description' => 'Export customs clearance passed at Nhava Sheva (JNPT) container depot.',
                'location' => 'JNPT Port Depot, Mumbai',
                'occurred_at' => now()->subDays(9),
            ],
            [
                'stage' => 'dispatched',
                'title' => 'Vessel Loaded & Departed Origin Port',
                'description' => 'Container loaded aboard MSC Mediterranean Highway. Bill of Lading issued.',
                'location' => 'Arabian Sea',
                'occurred_at' => now()->subDays(8),
            ],
            [
                'stage' => 'in_transit',
                'title' => 'Ocean Freight In International Transit',
                'description' => 'Vessel cruising on schedule. Customs pre-clearance documents submitted to Junaid & Co.',
                'location' => 'International Waters - Approaching Suez Canal',
                'occurred_at' => now()->subDays(2),
            ],
        ];

        foreach ($timelineUpdates as $tUpdate) {
            TrackingUpdate::create([
                'order_id' => $order->id,
                'shipment_id' => $shipment->id,
                'stage' => $tUpdate['stage'],
                'title' => $tUpdate['title'],
                'description' => $tUpdate['description'],
                'location' => $tUpdate['location'],
                'updated_by_user_id' => $logisticsUser->id,
                'occurred_at' => $tUpdate['occurred_at'],
            ]);
        }

        // 14. Customer Reviews
        Review::create([
            'buyer_id' => $buyer->id,
            'product_id' => $createdProducts[0]->id,
            'supplier_id' => $supplierIndia->id,
            'order_id' => $order->id,
            'rating' => 5,
            'title' => 'Exceptional Forging Quality & Flawless 3.1 Certification',
            'comment' => 'Received high-pressure stainless flanges in Birmingham. Dimensional tolerances were pinpoint accurate and the MTR documentation passed our nuclear/oil inspection with flying colors.',
            'is_verified_buyer' => true,
        ]);
    }
}
