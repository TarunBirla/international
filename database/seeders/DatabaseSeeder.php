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

        // 4. Product Categories & Subcategories
        $categoriesData = [
            [
                'name' => 'Industrial Machinery',
                'slug' => 'industrial-machinery',
                'icon' => 'fa-industry',
                'description' => 'Heavy industrial machines, CNC turning centers, hydraulic presses, and automated production units.',
                'is_featured' => true,
                'subs' => ['CNC Machining Centers', 'Hydraulic Presses', 'Lathe & Milling Machines', 'Packaging & Bottling Lines'],
            ],
            [
                'name' => 'Electrical Equipment',
                'slug' => 'electrical-equipment',
                'icon' => 'fa-bolt',
                'description' => 'Industrial power transformers, high-torque 3-phase induction motors, and distribution switchgear.',
                'is_featured' => true,
                'subs' => ['3-Phase Electric Motors', 'Industrial Transformers', 'Switchgear & Power Panels', 'Backup Generators'],
            ],
            [
                'name' => 'Mechanical Components',
                'slug' => 'mechanical-components',
                'icon' => 'fa-cogs',
                'description' => 'Precision roller bearings, high-ratio gearboxes, drive shafts, and engineered fasteners.',
                'is_featured' => true,
                'subs' => ['Precision Bearings', 'Planetary Gearboxes', 'Shafts & Couplings', 'High-Tensile Fasteners'],
            ],
            [
                'name' => 'Spare Parts',
                'slug' => 'spare-parts',
                'icon' => 'fa-wrench',
                'description' => 'Replacement parts, industrial fluid valves, hydraulic seals, and pneumatic cylinders.',
                'is_featured' => true,
                'subs' => ['Forged Pipe Flanges', 'Industrial Ball Valves', 'Pneumatic Actuators', 'Hydraulic Cylinders'],
            ],
            [
                'name' => 'Construction Equipment',
                'slug' => 'construction-equipment',
                'icon' => 'fa-hard-hat',
                'description' => 'Heavy earthmoving machinery, concrete mixers, tower cranes, and compaction rollers.',
                'is_featured' => true,
                'subs' => ['Excavators & Diggers', 'Concrete Batching Plants', 'Compaction Rollers', 'Tower Cranes'],
            ],
            [
                'name' => 'Tools and Hardware',
                'slug' => 'tools-and-hardware',
                'icon' => 'fa-tools',
                'description' => 'Carbide cutting inserts, precision metrology instruments, and heavy-duty workshop hand tools.',
                'is_featured' => false,
                'subs' => ['Carbide Cutting Inserts', 'Digital Calipers & Gauges', 'Torque Wrenches', 'Grinding Discs'],
            ],
            [
                'name' => 'Industrial Automation',
                'slug' => 'industrial-automation',
                'icon' => 'fa-robot',
                'description' => 'Robotic arms, PLC programmable controllers, frequency inverters, and optoelectronic sensors.',
                'is_featured' => true,
                'subs' => ['Robotic Articulated Arms', 'PLC & SCADA Controllers', 'Variable Frequency Drives (VFD)', 'Optical & Proximity Sensors'],
            ],
            [
                'name' => 'Safety Equipment',
                'slug' => 'safety-equipment',
                'icon' => 'fa-shield-alt',
                'description' => 'Certified PPE, flame retardant workwear, toxic gas detectors, and safety barriers.',
                'is_featured' => false,
                'subs' => ['Industrial Helmets & Visors', 'Gas Detection Monitors', 'Safety Harnesses', 'Fire Suppression Systems'],
            ],
            [
                'name' => 'Raw Materials',
                'slug' => 'raw-materials',
                'icon' => 'fa-cubes',
                'description' => 'Stainless steel coils, structural aluminum billets, engineering polymers, and copper busbars.',
                'is_featured' => false,
                'subs' => ['Stainless Steel 316 Coils', 'Extruded Aluminum Billets', 'Industrial Polymers', 'Solid Copper Rods'],
            ],
            [
                'name' => 'Industrial Services',
                'slug' => 'industrial-services',
                'icon' => 'fa-handshake',
                'description' => 'Custom CNC manufacturing, plant relocation, calibration testing, and technical installation.',
                'is_featured' => false,
                'subs' => ['Custom CNC Machining Services', 'NDT Testing & Quality Audits', 'Plant Machinery Commissioning'],
            ],
        ];

        $categoryMap = [];
        $order = 1;
        foreach ($categoriesData as $catData) {
            $parent = Category::create([
                'name' => $catData['name'],
                'slug' => $catData['slug'],
                'icon' => $catData['icon'],
                'description' => $catData['description'],
                'is_featured' => $catData['is_featured'],
                'sort_order' => $order++,
            ]);
            $categoryMap[$catData['slug']] = $parent;

            foreach ($catData['subs'] as $subName) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $subName,
                    'slug' => \Illuminate\Support\Str::slug($subName),
                    'icon' => $catData['icon'],
                    'description' => "Subcategory for {$subName}",
                    'is_featured' => false,
                    'sort_order' => $order++,
                ]);
            }
        }

        // 5. Products across India, China, Europe
        $productsData = [
            // India Products
            [
                'supplier_id' => $supplierIndia->id,
                'company_id' => $companyIndia->id,
                'category_id' => $categoryMap['spare-parts']->id,
                'name' => 'ASME B16.5 Class 300 Stainless Steel 316L Forged Flanges',
                'slug' => 'asme-b165-class-300-ss316l-forged-flanges',
                'sku' => 'IND-FLG-316L-01',
                'hs_code' => '7307.21.00',
                'short_description' => 'Heavy-duty forged stainless steel weld-neck flanges designed for high-pressure petrochemical, maritime, and UK process piping.',
                'full_description' => 'Manufactured in Pune, India according to ASME B16.5 and EN 1092-1 standards. Tested 100% with ultrasonic non-destructive testing (NDT), positive material identification (PMI), and complete EN 10204 3.1 mill test certificates. Suitable for offshore pipelines, chemical refineries, and power generation.',
                'technical_specifications' => [
                    'Standard' => 'ASME B16.5 / ANSI / EN 1092-1',
                    'Material Grade' => 'ASTM A182 F316 / F316L Stainless Steel',
                    'Pressure Class' => 'Class 150 to Class 2500 (PN16 to PN420)',
                    'Size Range' => '1/2 Inch to 24 Inch (DN15 - DN600)',
                    'Facing' => 'Raised Face (RF) / Ring Type Joint (RTJ)',
                    'Surface Treatment' => 'Rust-proof Anti-Corrosion Transparent Coating',
                    'Certification' => 'EN 10204 3.1 MTR, NACE MR0175 Compliant',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&h=600&fit=crop',
                'currency' => 'GBP',
                'base_price' => 45.00,
                'price_on_request' => false,
                'moq' => 20,
                'unit' => 'Pieces',
                'volume_pricing' => [
                    ['min' => 20, 'max' => 99, 'price' => 45.00],
                    ['min' => 100, 'max' => 499, 'price' => 38.50],
                    ['min' => 500, 'max' => 2000, 'price' => 32.00],
                ],
                'weight_kg' => 4.80,
                'dimensions' => '22 x 22 x 6 cm',
                'lead_time_days' => 18,
                'manufacturing_capacity' => '25,000 Pieces / Month',
                'origin_country' => 'India',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 1500,
            ],
            [
                'supplier_id' => $supplierIndia->id,
                'company_id' => $companyIndia->id,
                'category_id' => $categoryMap['electrical-equipment']->id,
                'name' => 'High-Efficiency IE3 30kW 3-Phase Squirrel Cage Induction Motor',
                'slug' => 'high-efficiency-ie3-30kw-3-phase-induction-motor',
                'sku' => 'IND-MOT-IE3-30KW',
                'hs_code' => '8501.52.20',
                'short_description' => 'Premium efficiency cast iron industrial electric motor for pumps, compressors, conveyers, and heavy industrial gearboxes.',
                'full_description' => 'Certified IE3 Premium Efficiency electric motor manufactured under ISO 9001:2015. Wound with 100% high-grade electrolytic copper wire, vacuum pressure impregnated (VPI) insulation Class F with Class B temperature rise. Built with heavy-duty SKF bearings and IP55 weather-proof cast iron enclosure.',
                'technical_specifications' => [
                    'Rated Power' => '30 kW (40 HP)',
                    'Rated Voltage' => '400V / 690V AC, 50Hz (UK/EU Standard)',
                    'Speed (RPM)' => '1480 RPM (4-Pole)',
                    'Efficiency Class' => 'IE3 Premium Efficiency (IEC 60034-30)',
                    'Protection Level' => 'IP55 / IP56 Cast Iron Frame',
                    'Insulation Class' => 'Class F (with Class B temp rise 80K)',
                    'Cooling Method' => 'IC411 (Totally Enclosed Fan Cooled)',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?w=800&h=600&fit=crop',
                'currency' => 'GBP',
                'base_price' => 780.00,
                'price_on_request' => false,
                'moq' => 2,
                'unit' => 'Units',
                'volume_pricing' => [
                    ['min' => 2, 'max' => 9, 'price' => 780.00],
                    ['min' => 10, 'max' => 29, 'price' => 710.00],
                    ['min' => 30, 'max' => 100, 'price' => 640.00],
                ],
                'weight_kg' => 195.00,
                'dimensions' => '72 x 45 x 52 cm',
                'lead_time_days' => 21,
                'manufacturing_capacity' => '800 Units / Month',
                'origin_country' => 'India',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 120,
            ],
            [
                'supplier_id' => $supplierIndia->id,
                'company_id' => $companyIndia->id,
                'category_id' => $categoryMap['spare-parts']->id,
                'name' => 'High-Pressure Forged Steel Floating Ball Valve PN40 DN50',
                'slug' => 'high-pressure-forged-steel-floating-ball-valve-pn40',
                'sku' => 'IND-VLV-PN40-DN50',
                'hs_code' => '8481.80.79',
                'short_description' => 'Two-piece bolted body forged steel ball valve with PTFE/RPTFE seat for oil, gas, steam, and industrial water pipelines.',
                'full_description' => 'Engineered with blow-out proof stem, antistatic device, and fire-safe design tested to API 607. Body in ASTM A105 forged carbon steel or A182 F316 stainless steel with mirror-polished solid stainless ball.',
                'technical_specifications' => [
                    'Design Standard' => 'BS 5351 / API 6D / EN 12516',
                    'Nominal Pressure' => 'PN40 / Class 300',
                    'Body Material' => 'ASTM A105 / A182 F316',
                    'Temperature Range' => '-29°C to +200°C',
                    'Connection' => 'Flanged RF to EN 1092-1',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?w=800&h=600&fit=crop',
                'currency' => 'GBP',
                'base_price' => 115.00,
                'price_on_request' => false,
                'moq' => 10,
                'unit' => 'Pieces',
                'volume_pricing' => [
                    ['min' => 10, 'max' => 49, 'price' => 115.00],
                    ['min' => 50, 'max' => 199, 'price' => 98.00],
                    ['min' => 200, 'max' => 1000, 'price' => 85.00],
                ],
                'weight_kg' => 9.20,
                'dimensions' => '25 x 18 x 20 cm',
                'lead_time_days' => 14,
                'manufacturing_capacity' => '5,000 Units / Month',
                'origin_country' => 'India',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => false,
                'stock_quantity' => 450,
            ],

            // China Products
            [
                'supplier_id' => $supplierChina->id,
                'company_id' => $companyChina->id,
                'category_id' => $categoryMap['industrial-machinery']->id,
                'name' => 'High-Speed 5-Axis CNC Vertical Machining Center VMC-850',
                'slug' => 'high-speed-5-axis-cnc-vertical-machining-center-vmc850',
                'sku' => 'CHN-CNC-VMC850-5AX',
                'hs_code' => '8457.10.90',
                'short_description' => 'Ultra-precision 5-axis vertical machining center equipped with Siemens 840D / FANUC CNC controller and 24-tool automatic tool changer (ATC).',
                'full_description' => 'Designed for aerospace, automotive, medical equipment, and precision mold manufacturing. Features heavy meehanite cast iron bed with high-rigidity linear roller guideways, 12,000 RPM direct-drive ceramic bearing spindle, and high-precision Heidenhain optical linear scales.',
                'technical_specifications' => [
                    'X/Y/Z Axis Travel' => '850 x 500 x 550 mm',
                    'B/C Rotary Table' => 'Diameter 400 mm, 360° Continuous Rotation',
                    'Spindle Speed' => '12,000 - 15,000 RPM Direct Drive',
                    'Spindle Taper' => 'BT40 / HSK-A63',
                    'Tool Magazine' => '24-Pocket Arm Type ATC (Tool Change 1.5s)',
                    'Positioning Accuracy' => '±0.005 mm (ISO 230-2)',
                    'CNC Controller' => 'Siemens 840D sl or FANUC 0i-MF Plus',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&h=600&fit=crop',
                'currency' => 'GBP',
                'base_price' => 38500.00,
                'price_on_request' => false,
                'moq' => 1,
                'unit' => 'Set',
                'volume_pricing' => [
                    ['min' => 1, 'max' => 2, 'price' => 38500.00],
                    ['min' => 3, 'max' => 5, 'price' => 35500.00],
                    ['min' => 6, 'max' => 20, 'price' => 32800.00],
                ],
                'weight_kg' => 5500.00,
                'dimensions' => '280 x 240 x 275 cm',
                'lead_time_days' => 35,
                'manufacturing_capacity' => '40 Sets / Month',
                'origin_country' => 'China',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 8,
            ],
            [
                'supplier_id' => $supplierChina->id,
                'company_id' => $companyChina->id,
                'category_id' => $categoryMap['industrial-automation']->id,
                'name' => '6-Axis Industrial Robotic Articulated Arm (20kg Payload / 1850mm Reach)',
                'slug' => '6-axis-industrial-robotic-articulated-arm-20kg',
                'sku' => 'CHN-ROB-6AX-20KG',
                'hs_code' => '8479.89.97',
                'short_description' => 'High-precision automated industrial robot arm for palletizing, MIG/TIG welding, CNC machine tending, and pick-and-place assembly.',
                'full_description' => 'Features RV cycloidal reduction gears, Japanese Tamagawa high-resolution absolute encoders, and intuitive handheld teach pendant. Complete with ROS / Python SDK and industrial Ethernet EtherCAT interface.',
                'technical_specifications' => [
                    'Max Payload' => '20 kg',
                    'Working Reach Radius' => '1850 mm',
                    'Repeatability' => '±0.03 mm',
                    'Axes' => '6-Axis Full Articulation',
                    'Mounting' => 'Floor, Ceiling, or Incline',
                    'Controller Enclosure' => 'IP54 Standard / IP65 Arm Wrist',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=800&h=600&fit=crop',
                'currency' => 'GBP',
                'base_price' => 11200.00,
                'price_on_request' => false,
                'moq' => 1,
                'unit' => 'Set',
                'volume_pricing' => [
                    ['min' => 1, 'max' => 2, 'price' => 11200.00],
                    ['min' => 3, 'max' => 9, 'price' => 9800.00],
                    ['min' => 10, 'max' => 50, 'price' => 8600.00],
                ],
                'weight_kg' => 240.00,
                'dimensions' => '95 x 75 x 140 cm',
                'lead_time_days' => 20,
                'manufacturing_capacity' => '120 Sets / Month',
                'origin_country' => 'China',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 25,
            ],
            [
                'supplier_id' => $supplierChina->id,
                'company_id' => $companyChina->id,
                'category_id' => $categoryMap['industrial-automation']->id,
                'name' => 'Modular Industrial PLC Controller CPU with Modbus TCP & EtherCAT',
                'slug' => 'modular-industrial-plc-controller-cpu',
                'sku' => 'CHN-PLC-MOD-32IO',
                'hs_code' => '8537.10.91',
                'short_description' => 'High-performance DIN rail programmable logic controller with 32 digital I/O, analog inputs, and dual gigabit industrial Ethernet.',
                'full_description' => 'IEC 61131-3 compliant programming (Ladder Diagram, Structured Text, Function Block). Fast instruction execution time of 0.02 microseconds, built-in real-time clock, SD card data logging, and web server for remote diagnostics.',
                'technical_specifications' => [
                    'Inputs / Outputs' => '16 Optically Isolated Inputs / 16 Transistor Outputs',
                    'Communication' => '2x Ethernet (Modbus TCP), RS485, CANopen',
                    'Memory' => '512 KB Program Memory, 2 MB Data Storage',
                    'Operating Temperature' => '-20°C to +60°C',
                    'Power Supply' => '24V DC (18V - 30V)',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?w=800&h=600&fit=crop',
                'currency' => 'GBP',
                'base_price' => 245.00,
                'price_on_request' => false,
                'moq' => 5,
                'unit' => 'Units',
                'volume_pricing' => [
                    ['min' => 5, 'max' => 19, 'price' => 245.00],
                    ['min' => 20, 'max' => 49, 'price' => 210.00],
                    ['min' => 50, 'max' => 200, 'price' => 180.00],
                ],
                'weight_kg' => 1.20,
                'dimensions' => '14 x 10 x 9 cm',
                'lead_time_days' => 7,
                'manufacturing_capacity' => '3,000 Units / Month',
                'origin_country' => 'China',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => false,
                'stock_quantity' => 350,
            ],

            // Global & Automation Products
            [
                'supplier_id' => $supplierChina->id,
                'company_id' => $companyChina->id,
                'category_id' => $categoryMap['mechanical-components']->id,
                'name' => 'High-Torque Heavy Duty Planetary Gearbox Ratio 25:1 (DIN ISO Standard)',
                'slug' => 'high-torque-heavy-duty-planetary-gearbox-ratio-25-1',
                'sku' => 'GER-GBX-PLN-25',
                'hs_code' => '8483.40.22',
                'short_description' => 'Precision planetary reduction gearbox with case-hardened alloy steel gears and 97% transmission efficiency.',
                'full_description' => 'Features ground helical planetary gear teeth with low backlash (< 3 arcmin), synthetic long-life lubrication, and viton double-lip radial shaft seals. Built to withstand continuous high shock loads in marine winches, mining crushers, and automated assembly machinery.',
                'technical_specifications' => [
                    'Nominal Output Torque' => '8,500 Nm (Peak Shock Torque 18,000 Nm)',
                    'Gear Ratio' => '25:1 Double Stage Reduction',
                    'Torsional Backlash' => '< 3 arcmin (Ultra-Precision)',
                    'Efficiency' => '>= 97% at rated speed',
                    'Shaft Type' => 'Solid keyed output shaft Ø 90 mm',
                    'Housing Material' => 'Nodular ductile cast iron GGG-40',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&h=600&fit=crop',
                'currency' => 'GBP',
                'base_price' => 2850.00,
                'price_on_request' => false,
                'moq' => 1,
                'unit' => 'Units',
                'volume_pricing' => [
                    ['min' => 1, 'max' => 4, 'price' => 2850.00],
                    ['min' => 5, 'max' => 14, 'price' => 2550.00],
                    ['min' => 15, 'max' => 50, 'price' => 2250.00],
                ],
                'weight_kg' => 165.00,
                'dimensions' => '65 x 48 x 50 cm',
                'lead_time_days' => 15,
                'manufacturing_capacity' => '200 Units / Month',
                'origin_country' => 'China',
                'direct_purchase_enabled' => true,
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 18,
            ],
            [
                'supplier_id' => $supplierChina->id,
                'company_id' => $companyChina->id,
                'category_id' => $categoryMap['industrial-machinery']->id,
                'name' => '250-Ton CNC Servo Hydraulic Stamping & Forming Press',
                'slug' => '250-ton-cnc-servo-hydraulic-stamping-forming-press',
                'sku' => 'GER-PRS-HYD-250T',
                'hs_code' => '8479.89.97',
                'short_description' => 'Precision 4-column servo-hydraulic forming and deep-drawing press with German Bosch Rexroth hydraulic proportional valves and touchscreen CNC control.',
                'full_description' => 'Top-tier German industrial stamping press offering variable pressing speed profiles, electronic linear transducers for 0.01 mm position repeatability, and light-curtain safety interlocks complying with EN 693 European safety standards.',
                'technical_specifications' => [
                    'Nominal Pressing Force' => '2500 kN (250 Metric Tons)',
                    'Bolster Table Size' => '1200 x 1000 mm with T-slots',
                    'Daylight Opening' => '900 mm',
                    'Ram Stroke' => '600 mm adjustable',
                    'Hydraulic Power Unit' => 'Bosch Rexroth variable displacement pump',
                    'Safety Compliance' => 'CE Category 4, Sick safety light curtains',
                ],
                'main_image' => 'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?w=800&h=600&fit=crop',
                'currency' => 'GBP',
                'base_price' => 54000.00,
                'price_on_request' => true,
                'moq' => 1,
                'unit' => 'Set',
                'volume_pricing' => null,
                'weight_kg' => 8500.00,
                'dimensions' => '220 x 180 x 360 cm',
                'lead_time_days' => 45,
                'manufacturing_capacity' => '15 Sets / Month',
                'origin_country' => 'Germany',
                'direct_purchase_enabled' => false, // Quotation only
                'status' => 'active',
                'is_featured' => true,
                'stock_quantity' => 4,
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
            'category_id' => $categoryMap['spare-parts']->id,
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
            'category_id' => $categoryMap['electrical-equipment']->id,
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
            'product_id' => $createdProducts[5]->id,
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
