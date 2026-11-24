# 🚀 Perlcon International B2B Marketplace - Server Deployment & Setup Guide

Perlcon International ek complete hybrid B2B Industrial Marketplace hai (IndiaMART + Alibaba style), jo Indian, Chinese aur European manufacturers ko UK/Global buyers se connect karta hai. Isme full multi-role access control, RFQ quotation comparison, multi-supplier e-commerce order splitting, automated landed cost calculator (HS codes + UK Duty/VAT), customs clearance workflow (Junaid & Co.), 14-stage logistics shipment tracking, aur 1-Click Server Database Auto-Setup shamil hai.

---

## 🌐 Server Par Database Banane Ka Route (Web Route Auto-Setup)

Jab aap is project ko kisi bhi cPanel, Plesk, VPS ya shared hosting server par upload karenge aur aapke paas SSH / Terminal access nahi hoga, tab aapko bas browser me ye route open karna hai:

### **Method 1: Interactive Web Setup Wizard (Recommended)**
```text
https://your-domain.com/setup-db
```
- Is page par server requirements aur database connectivity check hoti hai.
- Ek click me `CREATE DATABASE IF NOT EXISTS`, Migrations, aur 100% realistic Demo Data seed ho jata hai.

### **Method 2: 1-Click Instant Auto-Setup (Zero Click)**
```text
https://your-domain.com/setup-db/quick
```
*Ya phir:*
```text
https://your-domain.com/setup-db/run
```
*Ya:*
```text
https://your-domain.com/install
```
> **Kya hota hai is route ko hit karne par?**
> 1. `.env` file se database host, username, password aur database name read karta hai.
> 2. Agar MySQL database create nahi hai, to direct PDO connection se **`CREATE DATABASE IF NOT EXISTS \`dbname\``** execute karta hai.
> 3. Saari 17 migrations run karta hai (`php artisan migrate --force`).
> 4. Saare 49 categories, 8 users, realistic industrial products, HS duty rules, RFQs, quotes aur tracking records seed karta hai (`php artisan db:seed --force`).
> 5. Storage symlink create karta hai (`php artisan storage:link`).
> 6. Configuration aur Route cache clear karta hai.

---

## 📁 cPanel / Shared Hosting Par Upload Karne Ka Step-by-Step Tarika

1. **ZIP Banayein:**
   - Apne project folder ki zip banayein (`vendor` folder include kar sakte hain ya server par `composer install` chala sakte hain).

2. **cPanel File Manager Me Upload Karein:**
   - Shared hosting me files ko `public_html` ke bahar ek folder (jaise `perlcon_core`) me extract karein, aur `public` folder ke andar ka saara content `public_html` me daalein.
   - Ya phir agar primary domain hai to document root ko `public` folder par point kar dein.

3. **.env File Update Karein:**
   - Server par `.env` file open karein aur apna MySQL database details set karein:
   ```env
   APP_NAME="Perlcon International"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_cpanel_dbname
   DB_USERNAME=your_cpanel_dbuser
   DB_PASSWORD=your_cpanel_password
   ```

4. **Browser Me Setup Route Hit Karein:**
   - Browser open karein aur type karein:
   ```text
   https://your-domain.com/setup-db/quick
   ```
   - Success screen dikhai degi: **"Database Setup & Seeding Completed Successfully!"**

---

## 🔑 Demo Login Credentials (All Roles Ready)

Default password sabhi accounts ka: **`password123`**

| Role | Email | Name / Company | Country |
|---|---|---|---|
| 👑 **Super Admin** | `admin@perlcon.com` | Perlcon Platform Super Admin | United Kingdom |
| 🛡️ **Operations Admin** | `manager@perlcon.com` | Operations & Verification Lead | United Kingdom |
| 🛒 **Verified Buyer** | `buyer@perlcon.com` | Apex British Industrial Contracting Ltd | United Kingdom |
| 🏭 **Supplier (India)** | `supplier.india@perlcon.com` | Bharat Heavy Forge & Engineering Ltd | India 🇮🇳 |
| 🏭 **Supplier (China)** | `supplier.china@perlcon.com` | Shenzhen Precision CNC & Automation Co | China 🇨🇳 |
| 🏭 **Supplier (Europe)** | `supplier.europe@perlcon.com` | Vanguard Industrial Machinery GMBH | Germany 🇩🇪 |
| 🚢 **Logistics Partner** | `logistics@perlcon.com` | Global Freight & Intermodal Logistics Ltd | United Kingdom |
| 🛃 **Customs Partner** | `customs@perlcon.com` | Junaid & Co. Customs Clearance Specialists | United Kingdom |

---

## 🛠️ Key Features Built in Perlcon International

1. **Light Theme & Clean White UI:**
   - 100% white background (`#ffffff`), soft slate cards (`#f8fafc`), clean border structure (`#e2e8f0`), professional industrial blue palette (`#1e40af`, `#0284c7`), high contrast.

2. **Hierarchical Industrial Categories (49 Categories):**
   - Industrial Machinery, CNC & Lathe, Electrical Equipment, Transformers, Switchgear, Hydraulics & Pneumatics, Fasteners & Forgings, Industrial Automation, Raw Materials, etc.

3. **Multi-Country Supplier Directory & Profiles:**
   - Verified supplier badges, plant capacity, ISO certifications, export destinations (India, China, Europe).

4. **Dynamic RFQ & Multi-Supplier Quotation Comparator:**
   - Buyers post custom RFQs with technical specifications, CAD drawing uploads, and delivery deadlines.
   - Suppliers submit competitive quotes with volume tiered pricing, shipping lead times, and port origins.
   - Interactive side-by-side quotation comparison table with 1-click **Accept & Convert to Purchase Order**.

5. **Multi-Supplier Cart & Split Purchase Orders:**
   - Buyer can add products from different suppliers (e.g., India + China) in a single checkout.
   - Platform automatically splits the master customer order into separate supplier purchase orders (POs) with dedicated tracking numbers.

6. **Automated UK Landed Cost & Customs Duty Calculator:**
   - Built-in UK trade tariff engine covering industrial HS Codes (8457, 8501, 8481, 7318, 8413, 8537, 7208, 9031).
   - Calculates FOB + Freight + Insurance = CIF Value + UK Tariff Duty + 20% UK Import VAT + Customs Clearance Fees.

7. **Junaid & Co. Customs Clearance Module:**
   - Dedicated portal for customs clearance agents.
   - C88 single administrative document filing, HS code verification, Duty Deferment accounts, Postponed VAT Accounting (PVA), and green-channel clearance release.

8. **14-Stage Visual Logistics Stepper:**
   - Visual progress bar from PO Placement -> Factory Production -> Pre-Shipment Inspection -> Port Departure -> Sea/Air Transit -> UK Port Arrival -> Customs Clearance -> Inland Dispatch -> Delivered.

9. **Direct Artisan CLI Command:**
   - Agar aapke paas SSH terminal access hai, to aap terminal se bhi chala sakte hain:
   ```bash
   php artisan app:setup-db
   # Ya fresh clean setup ke liye:
   php artisan app:setup-db --fresh
   ```
