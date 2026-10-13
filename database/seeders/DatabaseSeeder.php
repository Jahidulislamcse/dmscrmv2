<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\{
    User,
    Service,
    LeadStage,
    Client,
    ClientService,
    Lead,
    LeadRequirement,
    LeadTimeline,
    Requisition,
    RequisitionItem,
    CustomStatus,
    Task,
    TaskProgress,
    ContentPost,
    Campaign,
    Meeting,
    Invoice,
    InvoiceItem,
    ReminderTemplate,
    Reminder,
    ExpenseCategory,
    Expense,
    Target,
    TargetItem,
    TargetDeal,
    Worklog,
    Notification,
    MyWorkItem,
    Followup,
    Attendance,
    EmployeeSalary
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 0. Safe Truncate ──
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tables = [
            'users',
            'services',
            'lead_stages',
            'expense_categories',
            'expenses',
            'reminder_templates',
            'reminders',
            'custom_statuses',
            'clients',
            'client_services',
            'leads',
            'lead_requirements',
            'lead_timelines',
            'meetings',
            'meeting_attendees',
            'followups',
            'requisitions',
            'requisition_items',
            'requisition_team',
            'tasks',
            'task_progresses',
            'content_posts',
            'campaigns',
            'invoices',
            'invoice_items',
            'targets',
            'target_items',
            'target_deals',
            'worklogs',
            'notifications',
            'my_work_items',
            'attendances',
            'employee_salaries',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── 1. Users (All 10 Tiers + Karim) ──
        $usersData = [
            [
                'name'              => 'Mehedi Hasan',
                'username'          => 'admin',
                'email'             => 'admin@dmsagency.com',
                'phone'             => '+880 1711-100200',
                'password'          => 'admin123',
                'role'              => 'owner',
                'designation'       => 'CEO & Agency Managing Director',
                'department'        => 'Executive Leadership',
                'joining_date'      => '2024-01-01',
                'base_salary'       => 120000.00,
                'emergency_contact' => 'Dr. Hasan (+880 1711-998811)',
                'color'             => '#f59e0b',
                'active'            => true,
            ],
            [
                'name'              => 'Farhan Manager',
                'username'          => 'manager',
                'email'             => 'manager@dmsagency.com',
                'phone'             => '+880 1712-200300',
                'password'          => 'manager123',
                'role'              => 'admin',
                'designation'       => 'Chief Operating Officer (COO)',
                'department'        => 'Agency Operations & Finance',
                'joining_date'      => '2024-03-01',
                'base_salary'       => 85000.00,
                'emergency_contact' => 'Fahim Manager (+880 1712-887766)',
                'color'             => '#6366f1',
                'active'            => true,
            ],
            [
                'name'              => 'Rahim Uddin',
                'username'          => 'rahim',
                'email'             => 'rahim@dmsagency.com',
                'phone'             => '+880 1713-300400',
                'password'          => 'rahim123',
                'role'              => 'sales',
                'designation'       => 'Senior Business Development Lead',
                'department'        => 'Sales & Partnerships',
                'joining_date'      => '2024-06-15',
                'base_salary'       => 60000.00,
                'emergency_contact' => 'Mrs. Rahim (+880 1713-776655)',
                'color'             => '#3b82f6',
                'active'            => true,
            ],
            [
                'name'              => 'Karim Sheikh',
                'username'          => 'karim',
                'email'             => 'karim@dmsagency.com',
                'phone'             => '+880 1714-400500',
                'password'          => 'karim123',
                'role'              => 'sales',
                'designation'       => 'B2B Enterprise Account Executive',
                'department'        => 'Sales & Partnerships',
                'joining_date'      => '2024-08-01',
                'base_salary'       => 55000.00,
                'emergency_contact' => 'Abul Sheikh (+880 1714-665544)',
                'color'             => '#8b5cf6',
                'active'            => true,
            ],
            [
                'name'              => 'Sara Begum',
                'username'          => 'sara',
                'email'             => 'sara@dmsagency.com',
                'phone'             => '+880 1715-500600',
                'password'          => 'sara123',
                'role'              => 'smm',
                'designation'       => 'Head of Social Media & Content Strategy',
                'department'        => 'SMM & Editorial Operations',
                'joining_date'      => '2024-04-10',
                'base_salary'       => 52000.00,
                'emergency_contact' => 'Salma Begum (+880 1715-554433)',
                'color'             => '#f97316',
                'active'            => true,
            ],
            [
                'name'              => 'Rafi Ahmed',
                'username'          => 'rafi',
                'email'             => 'rafi@dmsagency.com',
                'phone'             => '+880 1716-600700',
                'password'          => 'rafi123',
                'role'              => 'designer',
                'designation'       => 'Lead Visual & Brand Identity Designer',
                'department'        => 'Creative Production',
                'joining_date'      => '2024-05-01',
                'base_salary'       => 48000.00,
                'emergency_contact' => 'Rina Ahmed (+880 1716-443322)',
                'color'             => '#ec4899',
                'active'            => true,
            ],
            [
                'name'              => 'Nafi Islam',
                'username'          => 'nafi',
                'email'             => 'nafi@dmsagency.com',
                'phone'             => '+880 1717-700800',
                'password'          => 'nafi123',
                'role'              => 'motion',
                'designation'       => 'Senior 3D & Kinetic Motion Artist',
                'department'        => 'Motion & VFX',
                'joining_date'      => '2024-07-20',
                'base_salary'       => 50000.00,
                'emergency_contact' => 'Nur Islam (+880 1717-332211)',
                'color'             => '#14b8a6',
                'active'            => true,
            ],
            [
                'name'              => 'Tanvir Editor',
                'username'          => 'tanvir',
                'email'             => 'tanvir@dmsagency.com',
                'phone'             => '+880 1718-800900',
                'password'          => 'tanvir123',
                'role'              => 'video',
                'designation'       => 'Lead Video Editor & Colorist',
                'department'        => 'Video Production',
                'joining_date'      => '2024-09-01',
                'base_salary'       => 45000.00,
                'emergency_contact' => 'Tareq Editor (+880 1718-221100)',
                'color'             => '#f43f5e',
                'active'            => true,
            ],
            [
                'name'              => 'Tariq Hassan',
                'username'          => 'tariq',
                'email'             => 'tariq@dmsagency.com',
                'phone'             => '+880 1719-900100',
                'password'          => 'tariq123',
                'role'              => 'seo',
                'designation'       => 'Lead Technical SEO & Full-Stack Engineer',
                'department'        => 'Engineering & Organic Growth',
                'joining_date'      => '2024-02-15',
                'base_salary'       => 65000.00,
                'emergency_contact' => 'Nusrat Hassan (+880 1719-110099)',
                'color'             => '#10b981',
                'active'            => true,
            ],
            [
                'name'              => 'Sabbir Ads',
                'username'          => 'sabbir',
                'email'             => 'sabbir@dmsagency.com',
                'phone'             => '+880 1720-001122',
                'password'          => 'sabbir123',
                'role'              => 'mediabuyer',
                'designation'       => 'Senior Performance Marketer & Media Buyer',
                'department'        => 'Paid Performance Media',
                'joining_date'      => '2024-05-15',
                'base_salary'       => 58000.00,
                'emergency_contact' => 'Saif Ads (+880 1720-998877)',
                'color'             => '#0ea5e9',
                'active'            => true,
            ],
            [
                'name'              => 'Joy Team',
                'username'          => 'joy',
                'email'             => 'joy@dmsagency.com',
                'phone'             => '+880 1721-112233',
                'password'          => 'joy123',
                'role'              => 'employee',
                'designation'       => 'Operations & Account Support Associate',
                'department'        => 'General Operations',
                'joining_date'      => '2025-01-10',
                'base_salary'       => 32000.00,
                'emergency_contact' => 'Fatema (+880 1721-887766)',
                'color'             => '#64748b',
                'active'            => true,
            ],
        ];

        $u = [];
        foreach ($usersData as $data) {
            $u[$data['username']] = User::create($data);
        }

        // ── 2. Services ──
        $servicesData = [
            ['name' => 'Social Media Management (SMM 360)', 'base_price' => 35000, 'unit' => 'month'],
            ['name' => 'Meta Ads Performance Marketing',    'base_price' => 25000, 'unit' => 'month'],
            ['name' => 'Google Ads PPC & Search Optimization','base_price' => 22000, 'unit' => 'month'],
            ['name' => 'Creative Design & Branding Suite',   'base_price' => 800,   'unit' => 'post'],
            ['name' => 'Motion Graphics & 2D Animations',    'base_price' => 4500,  'unit' => 'video'],
            ['name' => 'Reels & Short-form Video Production','base_price' => 3500,  'unit' => 'video'],
            ['name' => 'SEO & Technical Organic Growth',     'base_price' => 28000, 'unit' => 'month'],
            ['name' => 'E-Commerce Web Design & Development','base_price' => 65000, 'unit' => 'project'],
            ['name' => 'Full-Funnel Content Writing & Copy', 'base_price' => 500,   'unit' => 'article'],
            ['name' => 'Commercial Product Photography Shoot','base_price' => 18000, 'unit' => 'session'],
        ];

        $srv = [];
        foreach ($servicesData as $s) {
            $srv[] = Service::create(array_merge($s, ['active' => true]));
        }

        // ── 3. Lead Stages ──
        $stagesData = [
            ['name' => 'New Lead',     'color' => '#94a3b8', 'order' => 1],
            ['name' => 'Contacted',    'color' => '#3b82f6', 'order' => 2],
            ['name' => 'Meeting Done', 'color' => '#8b5cf6', 'order' => 3],
            ['name' => 'Proposal Sent','color' => '#f59e0b', 'order' => 4],
            ['name' => 'Negotiation',  'color' => '#f97316', 'order' => 5],
            ['name' => 'Won',          'color' => '#10b981', 'order' => 6, 'is_won' => true],
            ['name' => 'Lost',         'color' => '#ef4444', 'order' => 7, 'is_lost' => true],
        ];

        $stg = [];
        foreach ($stagesData as $s) {
            $stg[] = LeadStage::create($s);
        }

        // ── 4. Expense Categories & Expenses ──
        $catsData = [
            ['name' => 'Banani Office Rent',      'color' => '#3b82f6'],
            ['name' => 'Utilities & Fiber Net',   'color' => '#8b5cf6'],
            ['name' => 'SaaS Software & AI Tools','color' => '#ec4899'],
            ['name' => 'Meta & Google Ads Credit','color' => '#f59e0b'],
            ['name' => 'Employee Salaries & KPI', 'color' => '#10b981'],
            ['name' => 'Camera & Studio Gear',    'color' => '#f97316'],
            ['name' => 'Office Refreshments & Misc','color' => '#64748b'],
        ];

        $expCats = [];
        foreach ($catsData as $c) {
            $expCats[] = ExpenseCategory::create($c);
        }

        $expensesData = [
            ['title' => 'Banani HQ Office Lease (Oct 2026)',    'amount' => 85000, 'category_id' => $expCats[0]->id, 'payment_method' => 'bank_transfer', 'date' => now()->startOfMonth()->toDateString()],
            ['title' => 'Dedicated 1Gbps Fiber Internet Line',   'amount' => 7500,  'category_id' => $expCats[1]->id, 'payment_method' => 'bkash',         'date' => now()->subDays(10)->toDateString()],
            ['title' => 'Adobe Creative Cloud Team License (10 Seats)', 'amount' => 24000, 'category_id' => $expCats[2]->id, 'payment_method' => 'card',   'date' => now()->subDays(8)->toDateString()],
            ['title' => 'Figma Organization Annual Subscription', 'amount' => 16500, 'category_id' => $expCats[2]->id, 'payment_method' => 'card',        'date' => now()->subDays(6)->toDateString()],
            ['title' => 'Meta Ad Account Threshold Credit Topup','amount' => 120000,'category_id' => $expCats[3]->id, 'payment_method' => 'card',        'date' => now()->subDays(4)->toDateString()],
            ['title' => 'Studio LED Softbox Lighting & Stands', 'amount' => 12500, 'category_id' => $expCats[5]->id, 'payment_method' => 'cash',        'date' => now()->subDays(12)->toDateString()],
            ['title' => 'Nespresso Coffee Beans & Team Snacks',  'amount' => 6800,  'category_id' => $expCats[6]->id, 'payment_method' => 'cash',        'date' => now()->subDays(2)->toDateString()],
            ['title' => 'Generator Backup Fuel & Electrical',    'amount' => 9200,  'category_id' => $expCats[1]->id, 'payment_method' => 'cash',        'date' => now()->subDays(15)->toDateString()],
        ];

        foreach ($expensesData as $e) {
            Expense::create(array_merge($e, ['created_by' => $u['admin']->id]));
        }

        // ── 5. Reminder Templates ──
        $remTemplates = [
            [
                'name'        => 'Friendly Pre-due Reminder',
                'title'       => 'Friendly Payment Notice',
                'type'        => 'whatsapp',
                'days_before' => 3,
                'body'        => "Assalamu Alaikum {client_name},\n\nThis is a friendly reminder from DMS Agency that invoice {invoice_number} for {balance_due} BDT is due on {due_date}.\n\nPlease let us know once transferred.\n\nBest regards,\nDMS Agency Finance",
            ],
            [
                'name'        => 'Invoice Due Today Notice',
                'title'       => 'Invoice Due Today Alert',
                'type'        => 'whatsapp',
                'days_before' => 0,
                'body'        => "Dear {client_name},\n\nYour retainer invoice {invoice_number} of {balance_due} BDT is due today ({due_date}).\n\nKindly clear payment today to ensure uninterrupted ad campaign delivery.\n\nDMS Agency",
            ],
            [
                'name'        => 'Urgent Overdue Notice',
                'title'       => 'Payment Overdue Notification',
                'type'        => 'email',
                'days_before' => 5,
                'body'        => "Dear {client_name},\n\nInvoice {invoice_number} ({balance_due} BDT) was due on {due_date} and is now overdue.\n\nPlease arrange remittance today or reply with payment confirmation.\n\nAccounts Department\nDMS Agency",
            ],
            [
                'name'        => 'Final Warning & Service Pause Alert',
                'title'       => 'Service Pause Warning',
                'type'        => 'sms',
                'days_before' => 10,
                'body'        => "URGENT: Invoice {invoice_number} for {client_name} is 10 days overdue. Active campaigns may be paused within 24h if {balance_due} BDT remains unpaid. Contact 01711203491.",
            ],
        ];

        $tpl = [];
        foreach ($remTemplates as $t) {
            $tpl[] = ReminderTemplate::create($t);
        }

        // ── 6. Default Custom Statuses ──
        $statuses = [
            ['name' => 'Backlog',       'color' => '#94a3b8', 'order' => 1, 'core_status' => 'pending',            'is_default' => true],
            ['name' => 'In Progress',   'color' => '#3b82f6', 'order' => 2, 'core_status' => 'in_progress',        'is_default' => true],
            ['name' => 'Quality Review','color' => '#8b5cf6', 'order' => 3, 'core_status' => 'done_pending_review','is_default' => true],
            ['name' => 'Approved & Done','color'=> '#10b981', 'order' => 4, 'core_status' => 'done',               'is_default' => true],
        ];
        foreach ($statuses as $st) {
            CustomStatus::create($st);
        }

        // ── 7. Realistic Clients ──
        $clientsData = [
            [
                'name'               => 'Aarong Lifestyle & Crafts',
                'company'            => 'BRAC-Aarong Limited',
                'phone'              => '+880 1711-203491',
                'email'              => 'marketing@aarong.com',
                'location'           => 'Aarong Centre, 346 Tejgaon I/A, Dhaka',
                'assigned_smm'       => $u['sara']->id,
                'assigned_sales'     => $u['rahim']->id,
                'status'             => 'active',
                'billing_cycle'      => 'monthly',
                'advance'            => 50000,
                'notes'              => 'Top lifestyle brand in BD. Full-funnel SMM, festive autumn campaigns, and Advantage+ Meta catalog sales.',
                'satisfaction_score' => 95,
                'onboarded_at'       => now()->subMonths(4)->toDateString(),
            ],
            [
                'name'               => 'Chaldal Fresh & Grocery',
                'company'            => 'Chaldal Limited',
                'phone'              => '+880 1819-482015',
                'email'              => 'growth@chaldal.com',
                'location'           => 'House 12, Road 1, Block A, Banani DOHS, Dhaka',
                'assigned_smm'       => $u['sara']->id,
                'assigned_sales'     => $u['rahim']->id,
                'status'             => 'active',
                'billing_cycle'      => 'monthly',
                'advance'            => 35000,
                'notes'              => 'Online grocery pioneer. Daily flash deal creative banners, rapid app installs, and Google Search campaigns.',
                'satisfaction_score' => 90,
                'onboarded_at'       => now()->subMonths(6)->toDateString(),
            ],
            [
                'name'               => 'Apex Footwear Ltd',
                'company'            => 'Apex Footwear Holdings',
                'phone'              => '+880 1912-349812',
                'email'              => 'sabrina.y@apexfootwearltd.com',
                'location'           => 'Apex Bhaban, Gulshan 1, Dhaka',
                'assigned_smm'       => $u['sara']->id,
                'assigned_sales'     => $u['rahim']->id,
                'status'             => 'active',
                'billing_cycle'      => 'monthly',
                'advance'            => 40000,
                'notes'              => 'Focus on Gen-Z Venturini sneakers launch and formal office comfort wear. High motion graphics and 4K reels demand.',
                'satisfaction_score' => 88,
                'onboarded_at'       => now()->subMonths(3)->toDateString(),
            ],
            [
                'name'               => 'BrainStation 23 FinTech',
                'company'            => 'Brain Station 23 PLC',
                'phone'              => '+880 1722-901234',
                'email'              => 'growth@brainstation-23.com',
                'location'           => '8th Floor, Mirpur 12 / Mohakhali Wireless, Dhaka',
                'assigned_smm'       => $u['sara']->id,
                'assigned_sales'     => $u['karim']->id,
                'status'             => 'active',
                'billing_cycle'      => 'monthly',
                'advance'            => 60000,
                'notes'              => 'Enterprise cloud & FinTech solutions. B2B LinkedIn thought leadership, technical SEO, and Google Search PPC.',
                'satisfaction_score' => 98,
                'onboarded_at'       => now()->subMonths(5)->toDateString(),
            ],
            [
                'name'               => 'Pathao Food & Courier',
                'company'            => 'Pathao Limited',
                'phone'              => '+880 1844-551122',
                'email'              => 'marketing@pathao.com',
                'location'           => 'Shanta Western Tower, Bir Uttam Mir Shawkat Sarak, Tejgaon, Dhaka',
                'assigned_smm'       => $u['sara']->id,
                'assigned_sales'     => $u['rahim']->id,
                'status'             => 'active',
                'billing_cycle'      => 'monthly',
                'advance'            => 55000,
                'notes'              => 'Super-app food delivery & courier. Viral weekend reels, late night food promo boosts, and influencer UGC cuts.',
                'satisfaction_score' => 92,
                'onboarded_at'       => now()->subMonths(3)->toDateString(),
            ],
            [
                'name'               => 'Bengal Meat Gourmet Cuts',
                'company'            => 'Bengal Meat Processing Industries Ltd',
                'phone'              => '+880 1713-998877',
                'email'              => 'retail@bengalmeat.com',
                'location'           => 'House 41, Road 27 (Old), Dhanmondi, Dhaka',
                'assigned_smm'       => $u['sara']->id,
                'assigned_sales'     => $u['karim']->id,
                'status'             => 'active',
                'billing_cycle'      => 'monthly',
                'advance'            => 25000,
                'notes'              => 'Premium organic beef, marinated steaks and cold cuts. Commercial food photography shoots and BBQ reels.',
                'satisfaction_score' => 85,
                'onboarded_at'       => now()->subMonths(2)->toDateString(),
            ],
            [
                'name'               => 'Yellow Lifestyle (Beximco)',
                'company'            => 'Beximco Apparels Ltd',
                'phone'              => '+880 1715-443322',
                'email'              => 'nadia@yellowlifestyle.net',
                'location'           => 'Sector 3, Uttara Model Town, Dhaka',
                'assigned_smm'       => $u['sara']->id,
                'assigned_sales'     => $u['rahim']->id,
                'status'             => 'onboarding',
                'billing_cycle'      => 'monthly',
                'advance'            => 45000,
                'notes'              => 'Modern youth apparel line. Complete seasonal brand identity revamp and headless Shopify optimization.',
                'satisfaction_score' => 82,
                'onboarded_at'       => now()->subWeeks(2)->toDateString(),
            ],
            [
                'name'               => 'Square Health Telemedicine',
                'company'            => 'Square Hospitals / Square Pharma',
                'phone'              => '+880 1730-123456',
                'email'              => 'online@squarehealth.com.bd',
                'location'           => '18/F Bir Uttam Qazi Nuruzzaman Sarak, Panthapath, Dhaka',
                'assigned_smm'       => $u['sara']->id,
                'assigned_sales'     => $u['karim']->id,
                'status'             => 'paused',
                'billing_cycle'      => 'monthly',
                'advance'            => 15000,
                'notes'              => 'Temporarily paused during Hospital ERP server migration. Resuming in November 2026.',
                'satisfaction_score' => 74,
                'onboarded_at'       => now()->subMonths(7)->toDateString(),
            ],
        ];

        $cl = [];
        foreach ($clientsData as $c) {
            $cl[] = Client::create($c);
        }

        // ── 8. Client Services Mapping ──
        // Aarong
        ClientService::create(['client_id' => $cl[0]->id, 'service_id' => $srv[0]->id, 'price' => 45000, 'qty' => 1]); // SMM 360
        ClientService::create(['client_id' => $cl[0]->id, 'service_id' => $srv[1]->id, 'price' => 25000, 'qty' => 1]); // Meta Ads
        ClientService::create(['client_id' => $cl[0]->id, 'service_id' => $srv[3]->id, 'price' => 800,   'qty' => 25]);// Design posts
        ClientService::create(['client_id' => $cl[0]->id, 'service_id' => $srv[5]->id, 'price' => 3500,  'qty' => 8]); // Reels

        // Chaldal
        ClientService::create(['client_id' => $cl[1]->id, 'service_id' => $srv[1]->id, 'price' => 30000, 'qty' => 1]); // Meta Ads
        ClientService::create(['client_id' => $cl[1]->id, 'service_id' => $srv[2]->id, 'price' => 25000, 'qty' => 1]); // Google Ads
        ClientService::create(['client_id' => $cl[1]->id, 'service_id' => $srv[3]->id, 'price' => 750,   'qty' => 30]);// Design posts

        // Apex
        ClientService::create(['client_id' => $cl[2]->id, 'service_id' => $srv[0]->id, 'price' => 40000, 'qty' => 1]); // SMM 360
        ClientService::create(['client_id' => $cl[2]->id, 'service_id' => $srv[4]->id, 'price' => 4500,  'qty' => 6]); // Motion Graphics
        ClientService::create(['client_id' => $cl[2]->id, 'service_id' => $srv[5]->id, 'price' => 3500,  'qty' => 10]);// Reels

        // BrainStation 23
        ClientService::create(['client_id' => $cl[3]->id, 'service_id' => $srv[6]->id, 'price' => 35000, 'qty' => 1]); // SEO Organic
        ClientService::create(['client_id' => $cl[3]->id, 'service_id' => $srv[2]->id, 'price' => 28000, 'qty' => 1]); // Google Ads
        ClientService::create(['client_id' => $cl[3]->id, 'service_id' => $srv[8]->id, 'price' => 800,   'qty' => 8]); // B2B Copy

        // Pathao
        ClientService::create(['client_id' => $cl[4]->id, 'service_id' => $srv[0]->id, 'price' => 50000, 'qty' => 1]); // SMM 360
        ClientService::create(['client_id' => $cl[4]->id, 'service_id' => $srv[1]->id, 'price' => 35000, 'qty' => 1]); // Meta Ads
        ClientService::create(['client_id' => $cl[4]->id, 'service_id' => $srv[5]->id, 'price' => 3500,  'qty' => 12]);// Reels

        // Bengal Meat
        ClientService::create(['client_id' => $cl[5]->id, 'service_id' => $srv[9]->id, 'price' => 18000, 'qty' => 1]); // Photography Shoot
        ClientService::create(['client_id' => $cl[5]->id, 'service_id' => $srv[0]->id, 'price' => 30000, 'qty' => 1]); // SMM 360
        ClientService::create(['client_id' => $cl[5]->id, 'service_id' => $srv[3]->id, 'price' => 800,   'qty' => 16]);// Designs

        // Yellow Lifestyle
        ClientService::create(['client_id' => $cl[6]->id, 'service_id' => $srv[7]->id, 'price' => 75000, 'qty' => 1]); // Web Dev
        ClientService::create(['client_id' => $cl[6]->id, 'service_id' => $srv[0]->id, 'price' => 45000, 'qty' => 1]); // SMM 360
        ClientService::create(['client_id' => $cl[6]->id, 'service_id' => $srv[5]->id, 'price' => 3500,  'qty' => 10]);// Reels

        // Square Health
        ClientService::create(['client_id' => $cl[7]->id, 'service_id' => $srv[6]->id, 'price' => 28000, 'qty' => 1]); // SEO
        ClientService::create(['client_id' => $cl[7]->id, 'service_id' => $srv[2]->id, 'price' => 20000, 'qty' => 1]); // Google Ads

        // ── 9. Realistic Leads ──
        $leadsData = [
            [
                'name'                  => 'Shwapno Super Shop (ACI)',
                'company'               => 'ACI Logistics Ltd',
                'phone'                 => '+880 1711-554433',
                'email'                 => 'marketing@shwapno.net',
                'location'              => 'Gulshan Avenue, Dhaka',
                'source'                => 'Referral',
                'stage_id'              => $stg[5]->id, // Won
                'assigned_to'           => $u['rahim']->id,
                'created_by'            => $u['rahim']->id,
                'budget'                => 95000,
                'notes'                 => 'Major retail chain. Deal closed successfully for monthly SMM + Meta Ads Retainer.',
                'next_followup'         => now()->addDays(7)->toDateString(),
                'converted_client_id'   => $cl[1]->id,
                'quotation_status'      => 'approved',
                'quotation_notes'       => 'Approved with 5% annual corporate discount.',
                'quotation_valid_until' => now()->addDays(30)->toDateString(),
                'quotation_discount'    => 5000,
                'quotation_tax_percent' => 5.00,
                'quotation_approved_by' => $u['admin']->id,
                'quotation_approved_at' => now()->subDays(3),
            ],
            [
                'name'                  => 'Walton Digi-Tech Industries',
                'company'               => 'Walton Hi-Tech PLC',
                'phone'                 => '+880 1811-223344',
                'email'                 => 'procurement@waltonbd.com',
                'location'              => 'Chandra, Gazipur & Motijheel HQ, Dhaka',
                'source'                => 'Website',
                'stage_id'              => $stg[4]->id, // Negotiation
                'assigned_to'           => $u['rahim']->id,
                'created_by'            => $u['rahim']->id,
                'budget'                => 125000,
                'notes'                 => 'Negotiating laptop & smartphone lineup digital campaign. Quotation submitted, waiting for approval.',
                'next_followup'         => now()->addDays(2)->toDateString(),
                'quotation_status'      => 'pending_approval',
                'quotation_notes'       => 'Special corporate pilot package submitted to Tanvir Ahmed.',
                'quotation_valid_until' => now()->addDays(15)->toDateString(),
                'quotation_discount'    => 8000,
                'quotation_tax_percent' => 0.00,
            ],
            [
                'name'                  => 'Kacchi Bhai Food Chains',
                'company'               => 'Kacchi Bhai Restaurants',
                'phone'                 => '+880 1911-778899',
                'email'                 => 'sohel@kacchibhai.bd',
                'location'              => 'Road 2, Dhanmondi, Dhaka',
                'source'                => 'Facebook Ad',
                'stage_id'              => $stg[3]->id, // Proposal Sent
                'assigned_to'           => $u['rahim']->id,
                'created_by'            => $u['rahim']->id,
                'budget'                => 65000,
                'notes'                 => 'Proposal sent for 12 monthly viral food reels and geo-targeted Instagram food delivery ads.',
                'next_followup'         => now()->addDays(3)->toDateString(),
                'quotation_status'      => 'draft',
                'quotation_notes'       => 'Draft proposal with 12 reels and 1 Meta ad campaign.',
                'quotation_valid_until' => now()->addDays(20)->toDateString(),
                'quotation_discount'    => 2000,
                'quotation_tax_percent' => 0.00,
            ],
            [
                'name'                  => 'Krav Maga Fitness BD',
                'company'               => 'Elite Krav Maga Gym',
                'phone'                 => '+880 1722-112233',
                'email'                 => 'coach@kravmagabd.com',
                'location'              => 'Road 11, Banani, Dhaka',
                'source'                => 'Instagram Ad',
                'stage_id'              => $stg[2]->id, // Meeting Done
                'assigned_to'           => $u['karim']->id,
                'created_by'            => $u['karim']->id,
                'budget'                => 40000,
                'notes'                 => 'Met with Coach Saimum. Interested in membership lead generation ads for female self-defense batches.',
                'next_followup'         => now()->addDays(4)->toDateString(),
                'quotation_status'      => 'draft',
            ],
            [
                'name'                  => 'GadgetHub BD (Daraz Top Seller)',
                'company'               => 'GadgetHub Limited',
                'phone'                 => '+880 1833-445566',
                'email'                 => 'sales@gadgethubbd.com',
                'location'              => 'Elephant Road, Dhaka',
                'source'                => 'Google Search',
                'stage_id'              => $stg[1]->id, // Contacted
                'assigned_to'           => $u['rahim']->id,
                'created_by'            => $u['rahim']->id,
                'budget'                => 35000,
                'notes'                 => 'First discovery call completed. Requires product video unboxings and TikTok viral setup.',
                'next_followup'         => now()->addDays(5)->toDateString(),
                'quotation_status'      => 'draft',
            ],
            [
                'name'                  => 'Star Tech & Engineering',
                'company'               => 'Star Tech Ltd',
                'phone'                 => '+880 1714-889900',
                'email'                 => 'asif@startechbd.com',
                'location'              => 'Multiplan Center, New Elephant Road, Dhaka',
                'source'                => 'Cold Outreach',
                'stage_id'              => $stg[0]->id, // New Lead
                'assigned_to'           => $u['karim']->id,
                'created_by'            => $u['karim']->id,
                'budget'                => 85000,
                'notes'                 => 'Fresh inbound lead via agency WhatsApp line. Looking for SEO keyword ranking dominance over Ryans.',
                'next_followup'         => now()->addDays(1)->toDateString(),
                'quotation_status'      => 'draft',
            ],
            [
                'name'                  => 'PandaMart QuickCommerce',
                'company'               => 'Delivery Hero Bangladesh',
                'phone'                 => '+880 1799-665544',
                'email'                 => 'vendor.ops@foodpanda.bd',
                'location'              => 'Mirpur DOHS, Dhaka',
                'source'                => 'Referral',
                'stage_id'              => $stg[5]->id, // Won
                'assigned_to'           => $u['rahim']->id,
                'created_by'            => $u['rahim']->id,
                'budget'                => 75000,
                'notes'                 => 'Flash discount dynamic creatives campaign won for Q4 festival season.',
                'next_followup'         => now()->addDays(10)->toDateString(),
                'converted_client_id'   => $cl[4]->id,
                'quotation_status'      => 'approved',
                'quotation_notes'       => 'Approved signed agreement.',
                'quotation_valid_until' => now()->addDays(45)->toDateString(),
                'quotation_discount'    => 0,
                'quotation_tax_percent' => 5.00,
                'quotation_approved_by' => $u['admin']->id,
                'quotation_approved_at' => now()->subDays(5),
            ],
            [
                'name'                  => 'PriyoShop B2B Commerce',
                'company'               => 'PriyoShop Limited',
                'phone'                 => '+880 1755-332211',
                'email'                 => 'growth@priyoshop.com',
                'location'              => 'Kawran Bazar C/A, Dhaka',
                'source'                => 'LinkedIn',
                'stage_id'              => $stg[6]->id, // Lost
                'assigned_to'           => $u['karim']->id,
                'created_by'            => $u['karim']->id,
                'budget'                => 50000,
                'notes'                 => 'Lost to in-house marketing hire. Keep in quarterly nurture email cadence.',
                'next_followup'         => now()->addMonths(3)->toDateString(),
                'quotation_status'      => 'rejected',
                'quotation_notes'       => 'Client opted for in-house team.',
            ],
        ];

        $lds = [];
        foreach ($leadsData as $ld) {
            $lds[] = Lead::create($ld);
        }

        // ── 10. Lead Requirements ──
        LeadRequirement::create(['lead_id' => $lds[0]->id, 'service_name' => 'Social Media Management (SMM 360)', 'qty' => 1, 'unit_price' => 45000, 'notes' => 'Full account handling']);
        LeadRequirement::create(['lead_id' => $lds[0]->id, 'service_name' => 'Meta Ads Performance Marketing',     'qty' => 1, 'unit_price' => 30000, 'notes' => 'Advantage+ Catalog']);
        LeadRequirement::create(['lead_id' => $lds[0]->id, 'service_name' => 'Creative Design & Branding Suite',    'qty' => 25,'unit_price' => 800,   'notes' => 'Festive post set']);

        LeadRequirement::create(['lead_id' => $lds[1]->id, 'service_name' => 'Google Ads PPC & Search Optimization', 'qty' => 1, 'unit_price' => 35000, 'notes' => 'Electronics high intent search']);
        LeadRequirement::create(['lead_id' => $lds[1]->id, 'service_name' => 'SEO & Technical Organic Growth',      'qty' => 1, 'unit_price' => 30000, 'notes' => 'Product page SEO']);
        LeadRequirement::create(['lead_id' => $lds[1]->id, 'service_name' => 'Motion Graphics & 2D Animations',     'qty' => 4, 'unit_price' => 6000,  'notes' => '3D product spins']);
        LeadRequirement::create(['lead_id' => $lds[1]->id, 'service_name' => 'E-Commerce Web Design & Development', 'qty' => 1, 'unit_price' => 40000, 'notes' => 'Promo landing page']);

        LeadRequirement::create(['lead_id' => $lds[2]->id, 'service_name' => 'Reels & Short-form Video Production', 'qty' => 12,'unit_price' => 3500,  'notes' => 'Viral food tasting reels']);
        LeadRequirement::create(['lead_id' => $lds[2]->id, 'service_name' => 'Meta Ads Performance Marketing',     'qty' => 1, 'unit_price' => 25000, 'notes' => 'Local Dhanmondi & Banani radius']);

        // ── 11. Lead Timelines ──
        LeadTimeline::create(['lead_id' => $lds[0]->id, 'user_id' => $u['rahim']->id, 'type' => 'call', 'text' => 'Initial pitch call with Mahbubur Rahman. Discussed Q4 campaign requirements.']);
        LeadTimeline::create(['lead_id' => $lds[0]->id, 'user_id' => $u['rahim']->id, 'type' => 'meeting', 'text' => 'In-person presentation at ACI Centre. Proposal warmly received.']);
        LeadTimeline::create(['lead_id' => $lds[0]->id, 'user_id' => $u['admin']->id, 'type' => 'status_change', 'text' => 'Proposal approved and deal marked as WON! Requisition created.']);

        LeadTimeline::create(['lead_id' => $lds[1]->id, 'user_id' => $u['rahim']->id, 'type' => 'meeting', 'text' => 'Virtual sync with Walton Digital team. Reviewed technical scope with Tariq (SEO).']);
        LeadTimeline::create(['lead_id' => $lds[1]->id, 'user_id' => $u['rahim']->id, 'type' => 'proposal', 'text' => 'Official quotation PDF generated and sent via portal.']);

        // ── 12. Meetings & Attendees ──
        $meetingsData = [
            [
                'client_id'     => $cl[0]->id,
                'lead_id'       => null,
                'created_by'    => $u['rahim']->id,
                'client_name'   => 'Aarong Lifestyle',
                'agenda'        => 'Autumn Festive Campaign Creative Storyboards & Shoot Schedule',
                'date'          => now()->subDays(2)->toDateString(),
                'time'          => '11:00:00',
                'duration'      => 60,
                'location'      => 'Aarong Centre Boardroom, Tejgaon',
                'status'        => 'completed',
                'outcome'       => 'Client approved 6 moodboards. Photography shoot scheduled for Thursday.',
                'next_action'   => 'Designer Rafi to finalize carousel layouts.',
                'next_followup' => now()->addDays(3)->toDateString(),
            ],
            [
                'client_id'     => $cl[2]->id,
                'lead_id'       => null,
                'created_by'    => $u['sara']->id,
                'client_name'   => 'Apex Footwear Ltd',
                'agenda'        => 'Venturini Sneaker Video Cut Review & TikTok Ads Strategy',
                'date'          => now()->subDays(1)->toDateString(),
                'time'          => '15:30:00',
                'duration'      => 45,
                'location'      => 'Google Meet (Virtual)',
                'status'        => 'completed',
                'outcome'       => 'Sabrina approved 1st cut. Requested font size enlargement on final 3 seconds CTA.',
                'next_action'   => 'Tanvir to render 4K version with revised audio master.',
                'next_followup' => now()->addDays(2)->toDateString(),
            ],
            [
                'client_id'     => null,
                'lead_id'       => $lds[1]->id,
                'created_by'    => $u['rahim']->id,
                'client_name'   => 'Walton Digi-Tech',
                'agenda'        => 'Commercial Proposal Negotiation & Contract Scope Finalization',
                'date'          => now()->addDays(1)->toDateString(),
                'time'          => '14:00:00',
                'duration'      => 60,
                'location'      => 'DMS Agency Conference Room, Banani',
                'status'        => 'scheduled',
                'outcome'       => null,
                'next_action'   => 'Prepare revised corporate SLA contract draft.',
                'next_followup' => now()->addDays(2)->toDateString(),
            ],
            [
                'client_id'     => $cl[3]->id,
                'lead_id'       => null,
                'created_by'    => $u['tariq']->id,
                'client_name'   => 'BrainStation 23 FinTech',
                'agenda'        => 'Technical Core Web Vitals & B2B International Keyword Strategy',
                'date'          => now()->addDays(3)->toDateString(),
                'time'          => '16:00:00',
                'duration'      => 45,
                'location'      => 'Zoom Meeting',
                'status'        => 'scheduled',
                'outcome'       => null,
                'next_action'   => 'Share SEMrush keyword comparison spreadsheet before call.',
                'next_followup' => now()->addDays(4)->toDateString(),
            ],
            [
                'client_id'     => null,
                'lead_id'       => $lds[2]->id,
                'created_by'    => $u['rahim']->id,
                'client_name'   => 'Kacchi Bhai Food Chains',
                'agenda'        => 'Food Reels Pilot Presentation & Tasting Shoot Concept',
                'date'          => now()->addDays(4)->toDateString(),
                'time'          => '12:30:00',
                'duration'      => 60,
                'location'      => 'Kacchi Bhai Dhanmondi Branch',
                'status'        => 'scheduled',
                'outcome'       => null,
                'next_action'   => 'Tanvir & Rafi to bring gimbal and lighting kit.',
                'next_followup' => now()->addDays(5)->toDateString(),
            ],
        ];

        $mts = [];
        foreach ($meetingsData as $md) {
            $m = Meeting::create($md);
            $mts[] = $m;
            // Attendees
            DB::table('meeting_attendees')->insert([
                ['meeting_id' => $m->id, 'user_id' => $u['admin']->id],
                ['meeting_id' => $m->id, 'user_id' => $u['rahim']->id],
                ['meeting_id' => $m->id, 'user_id' => $u['sara']->id],
            ]);
        }

        // ── 13. Follow-ups ──
        $followupsData = [
            [
                'title'        => 'Call Tanvir Ahmed regarding Walton quotation review',
                'description'  => 'Confirm whether procurement committee approved the 125,000 BDT SLA.',
                'type'         => 'call',
                'priority'     => 'urgent',
                'scheduled_at' => now()->addHours(3),
                'status'       => 'pending',
                'lead_id'      => $lds[1]->id,
                'assigned_to'  => $u['rahim']->id,
                'created_by'   => $u['rahim']->id,
            ],
            [
                'title'        => 'WhatsApp message to Kacchi Bhai regarding shooting date',
                'description'  => 'Send draft storyboard link and confirm branch availability.',
                'type'         => 'whatsapp',
                'priority'     => 'high',
                'scheduled_at' => now()->addDays(1),
                'status'       => 'pending',
                'lead_id'      => $lds[2]->id,
                'assigned_to'  => $u['rahim']->id,
                'created_by'   => $u['rahim']->id,
            ],
            [
                'title'        => 'In-person gym visit at Krav Maga Banani',
                'description'  => 'Inspect ambient gym lighting and meet with head fitness coach.',
                'type'         => 'visit',
                'priority'     => 'medium',
                'scheduled_at' => now()->addDays(2),
                'status'       => 'pending',
                'lead_id'      => $lds[3]->id,
                'assigned_to'  => $u['karim']->id,
                'created_by'   => $u['karim']->id,
            ],
            [
                'title'        => 'Payment follow-up with Square Health Accounts',
                'description'  => 'Inquired on overdue invoice INV-2026-010 (48,000 BDT).',
                'type'         => 'call',
                'priority'     => 'urgent',
                'scheduled_at' => now()->subDays(1),
                'status'       => 'completed',
                'outcome'      => 'Accounts manager stated check is in sign-off stage, promised payment within 48 hours.',
                'client_id'    => $cl[7]->id,
                'assigned_to'  => $u['karim']->id,
                'created_by'   => $u['karim']->id,
                'completed_at' => now()->subDays(1),
            ],
            [
                'title'        => 'WhatsApp follow-up with Aarong on Autumn Reel captions',
                'description'  => 'Sent copywriter brief for approval.',
                'type'         => 'whatsapp',
                'priority'     => 'medium',
                'scheduled_at' => now()->subDays(2),
                'status'       => 'completed',
                'outcome'      => 'Tanzim approved Bengali captions with minor word switch.',
                'client_id'    => $cl[0]->id,
                'assigned_to'  => $u['sara']->id,
                'created_by'   => $u['sara']->id,
                'completed_at' => now()->subDays(2),
            ],
        ];

        foreach ($followupsData as $f) {
            Followup::create($f);
        }

        // ── 14. Requisitions & Team Allocation ──
        $req1 = Requisition::create([
            'lead_id'             => $lds[0]->id,
            'submitted_by'        => $u['rahim']->id,
            'reviewed_by'         => $u['admin']->id,
            'assigned_smm'        => $u['sara']->id,
            'converted_client_id' => $cl[0]->id,
            'client_name'         => 'Aarong Lifestyle & Crafts',
            'company'             => 'BRAC-Aarong Limited',
            'phone'               => '+880 1711-203491',
            'email'               => 'marketing@aarong.com',
            'location'            => 'Tejgaon I/A, Dhaka',
            'total_value'         => 105000,
            'advance_paid'        => 50000,
            'billing_cycle'       => 'monthly',
            'status'              => 'approved',
            'special_notes'       => 'Full agency onboarding. Superadmin approved team assignment.',
            'admin_notes'         => 'Approved. Assigned Rafi (Design), Tanvir (Video), and Sabbir (Media Buyer).',
            'reviewed_at'         => now()->subDays(4),
        ]);

        RequisitionItem::create(['requisition_id' => $req1->id, 'service_name' => 'SMM 360 Full Retainer', 'qty' => 1, 'unit_price' => 45000, 'total' => 45000]);
        RequisitionItem::create(['requisition_id' => $req1->id, 'service_name' => 'Meta Advantage+ Ad Mgmt', 'qty' => 1, 'unit_price' => 25000, 'total' => 25000]);
        RequisitionItem::create(['requisition_id' => $req1->id, 'service_name' => 'Short-form Reels (8 cuts)', 'qty' => 8, 'unit_price' => 3500,  'total' => 28000]);
        RequisitionItem::create(['requisition_id' => $req1->id, 'service_name' => 'Ad Post Graphic Designs',  'qty' => 10,'unit_price' => 700,   'total' => 7000]);

        DB::table('requisition_team')->insert([
            ['requisition_id' => $req1->id, 'user_id' => $u['sara']->id],
            ['requisition_id' => $req1->id, 'user_id' => $u['rafi']->id],
            ['requisition_id' => $req1->id, 'user_id' => $u['tanvir']->id],
            ['requisition_id' => $req1->id, 'user_id' => $u['sabbir']->id],
        ]);

        $req2 = Requisition::create([
            'lead_id'             => $lds[1]->id,
            'submitted_by'        => $u['rahim']->id,
            'reviewed_by'         => null,
            'assigned_smm'        => null,
            'converted_client_id' => null,
            'client_name'         => 'Walton Digi-Tech Industries',
            'company'             => 'Walton Hi-Tech PLC',
            'phone'               => '+880 1811-223344',
            'email'               => 'procurement@waltonbd.com',
            'location'            => 'Motijheel HQ, Dhaka',
            'total_value'         => 125000,
            'advance_paid'        => 35000,
            'billing_cycle'       => 'monthly',
            'status'              => 'pending',
            'special_notes'       => 'Sales closed pending Admin review. Need Tariq for SEO and Nafi for 3D Motion.',
            'admin_notes'         => null,
            'reviewed_at'         => null,
        ]);

        RequisitionItem::create(['requisition_id' => $req2->id, 'service_name' => 'Google Ads Performance Search', 'qty' => 1, 'unit_price' => 35000, 'total' => 35000]);
        RequisitionItem::create(['requisition_id' => $req2->id, 'service_name' => 'SEO Technical Retainer',       'qty' => 1, 'unit_price' => 30000, 'total' => 30000]);
        RequisitionItem::create(['requisition_id' => $req2->id, 'service_name' => 'Motion 3D Animations',         'qty' => 4, 'unit_price' => 6000,  'total' => 24000]);
        RequisitionItem::create(['requisition_id' => $req2->id, 'service_name' => 'Campaign Landing Page',        'qty' => 1, 'unit_price' => 36000, 'total' => 36000]);

        // ── 15. Tasks & Progress (Kanban with Checklists & Attachments) ──
        $tasksData = [
            // Rafi (Designer)
            [
                'title'           => 'Design 6 High-Res Carousels for Aarong Autumn Festival',
                'client_id'       => $cl[0]->id,
                'assigned_to'     => $u['rafi']->id,
                'assigned_by'     => $u['sara']->id,
                'service_id'      => $srv[3]->id,
                'status'          => 'in_progress',
                'priority'        => 'high',
                'approval_status' => 'pending',
                'notes'           => 'Match ethnic earthy color palette with BRAC brand guidelines. 1080x1350 vertical carousel format.',
                'checklist'       => [
                    ['id' => 1, 'text' => 'Product cutouts & tone correction', 'done' => true],
                    ['id' => 2, 'text' => 'Bengali typographic headline alignment', 'done' => true],
                    ['id' => 3, 'text' => 'Client feedback & export 4x PNGs', 'done' => false],
                ],
                'attachments'     => [
                    ['name' => 'Aarong_Autumn_Moodboard.pdf', 'url' => 'https://assets.dmsagency.com/briefs/aarong_autumn.pdf', 'size' => '4.2MB'],
                    ['name' => 'Figma_Source_File.fig',       'url' => 'https://www.figma.com/file/aarong-autumn-carousels',      'size' => '18.4MB'],
                ],
                'deadline'        => now()->addDays(2)->toDateString(),
                'scheduled_date'  => now()->toDateString(),
                'estimated_hours' => 6.5,
                'qty'             => 6,
            ],
            [
                'title'           => 'Chaldal Flash Sale Banner Suite (Facebook & Mobile App)',
                'client_id'       => $cl[1]->id,
                'assigned_to'     => $u['rafi']->id,
                'assigned_by'     => $u['sara']->id,
                'service_id'      => $srv[3]->id,
                'status'          => 'done',
                'priority'        => 'high',
                'approval_status' => 'approved',
                'notes'           => 'Promote fresh seasonal vegetables flat 20% off.',
                'checklist'       => [
                    ['id' => 1, 'text' => 'Square 1080x1080 Feed banner', 'done' => true],
                    ['id' => 2, 'text' => 'Story 1080x1920 format',       'done' => true],
                    ['id' => 3, 'text' => 'App Push Notification banner', 'done' => true],
                ],
                'attachments'     => [
                    ['name' => 'Chaldal_Approved_Assets.zip', 'url' => 'https://assets.dmsagency.com/delivery/chaldal_flash.zip', 'size' => '8.6MB'],
                ],
                'deadline'        => now()->subDays(1)->toDateString(),
                'scheduled_date'  => now()->subDays(2)->toDateString(),
                'estimated_hours' => 4.0,
                'qty'             => 3,
            ],
            [
                'title'           => 'Apex Venturini Sneaker Editorial Campaign Post',
                'client_id'       => $cl[2]->id,
                'assigned_to'     => $u['rafi']->id,
                'assigned_by'     => $u['sara']->id,
                'service_id'      => $srv[3]->id,
                'status'          => 'done_pending_review',
                'priority'        => 'medium',
                'approval_status' => 'pending',
                'notes'           => 'Edgy urban aesthetic targeting university students.',
                'checklist'       => [
                    ['id' => 1, 'text' => 'Sneaker lighting contrast tweak', 'done' => true],
                    ['id' => 2, 'text' => 'Incorporate Venturini badge logo', 'done' => true],
                ],
                'deadline'        => now()->addDays(1)->toDateString(),
                'estimated_hours' => 3.5,
                'qty'             => 1,
            ],
            // Nafi (Motion Designer)
            [
                'title'           => 'Apex Venturini 15s Dynamic Kinetic 3D Logo Reveal',
                'client_id'       => $cl[2]->id,
                'assigned_to'     => $u['nafi']->id,
                'assigned_by'     => $u['manager']->id,
                'service_id'      => $srv[4]->id,
                'status'          => 'in_progress',
                'priority'        => 'high',
                'approval_status' => 'pending',
                'notes'           => 'High energy 120fps motion graphics with bass-heavy sound effect sync.',
                'checklist'       => [
                    ['id' => 1, 'text' => 'Cinema4D render passes', 'done' => true],
                    ['id' => 2, 'text' => 'After Effects compositing', 'done' => false],
                    ['id' => 3, 'text' => 'Sound FX audio master', 'done' => false],
                ],
                'attachments'     => [
                    ['name' => 'Apex_Audio_WAV.wav', 'url' => 'https://assets.dmsagency.com/audio/apex_bass.wav', 'size' => '12.1MB'],
                ],
                'deadline'        => now()->addDays(3)->toDateString(),
                'estimated_hours' => 8.0,
                'qty'             => 1,
            ],
            [
                'title'           => 'Pathao Courier Kinetic Typography Promo Reel',
                'client_id'       => $cl[4]->id,
                'assigned_to'     => $u['nafi']->id,
                'assigned_by'     => $u['sara']->id,
                'service_id'      => $srv[4]->id,
                'status'          => 'done',
                'priority'        => 'high',
                'approval_status' => 'approved',
                'notes'           => 'Deliver in 1080x1920 vertical 9:16 format for Instagram & TikTok.',
                'deadline'        => now()->subDays(2)->toDateString(),
                'estimated_hours' => 5.0,
                'qty'             => 1,
            ],
            // Tanvir (Video Editor)
            [
                'title'           => 'Aarong Handloom Artisan Documentary Reel (60s)',
                'client_id'       => $cl[0]->id,
                'assigned_to'     => $u['tanvir']->id,
                'assigned_by'     => $u['sara']->id,
                'service_id'      => $srv[5]->id,
                'status'          => 'done_pending_review',
                'priority'        => 'high',
                'approval_status' => 'pending',
                'notes'           => 'Footage from Tangail loom village. Warm organic color grade with acoustic flute soundtrack.',
                'checklist'       => [
                    ['id' => 1, 'text' => 'Rough cut & audio synch', 'done' => true],
                    ['id' => 2, 'text' => 'DaVinci Resolve color grade', 'done' => true],
                    ['id' => 3, 'text' => 'English subtitles styling', 'done' => true],
                ],
                'attachments'     => [
                    ['name' => 'Google_Drive_Raw_Footage.txt', 'url' => 'https://drive.google.com/drive/folders/aarong-raw-video', 'size' => '1KB'],
                ],
                'deadline'        => now()->addDays(1)->toDateString(),
                'estimated_hours' => 7.5,
                'qty'             => 1,
            ],
            [
                'title'           => 'Bengal Meat Ribeye Grilling ASMR Reel (30s)',
                'client_id'       => $cl[5]->id,
                'assigned_to'     => $u['tanvir']->id,
                'assigned_by'     => $u['sara']->id,
                'service_id'      => $srv[5]->id,
                'status'          => 'in_progress',
                'priority'        => 'medium',
                'approval_status' => 'pending',
                'notes'           => 'Sizzling steak audio amplification and butter basting closeups.',
                'deadline'        => now()->addDays(4)->toDateString(),
                'estimated_hours' => 4.5,
                'qty'             => 1,
            ],
            // Tariq (SEO & Web)
            [
                'title'           => 'BrainStation 23 Core Web Vitals & Technical Schema Fix',
                'client_id'       => $cl[3]->id,
                'assigned_to'     => $u['tariq']->id,
                'assigned_by'     => $u['admin']->id,
                'service_id'      => $srv[6]->id,
                'status'          => 'in_progress',
                'priority'        => 'high',
                'notes'           => 'Resolve LCP delays and configure FAQ Schema markup for FinTech solution pages.',
                'checklist'       => [
                    ['id' => 1, 'text' => 'WebP image compression pipeline', 'done' => true],
                    ['id' => 2, 'text' => 'Defer unused JavaScript bundles', 'done' => true],
                    ['id' => 3, 'text' => 'JSON-LD schema testing in Google Validator', 'done' => false],
                ],
                'deadline'        => now()->addDays(5)->toDateString(),
                'estimated_hours' => 8.0,
            ],
            [
                'title'           => 'Yellow Lifestyle Headless Shopify Performance Optimization',
                'client_id'       => $cl[6]->id,
                'assigned_to'     => $u['tariq']->id,
                'assigned_by'     => $u['manager']->id,
                'service_id'      => $srv[7]->id,
                'status'          => 'pending',
                'priority'        => 'high',
                'notes'           => 'Audit mobile checkout load time and bundle splitting.',
                'deadline'        => now()->addDays(8)->toDateString(),
                'estimated_hours' => 12.0,
            ],
            // Sabbir (Media Buyer)
            [
                'title'           => 'Configure Meta Advantage+ Shopping Campaign for Aarong',
                'client_id'       => $cl[0]->id,
                'assigned_to'     => $u['sabbir']->id,
                'assigned_by'     => $u['manager']->id,
                'service_id'      => $srv[1]->id,
                'status'          => 'done',
                'priority'        => 'high',
                'approval_status' => 'approved',
                'notes'           => 'Pixel verified, CAPI server event deductions tested.',
                'deadline'        => now()->subDays(3)->toDateString(),
                'estimated_hours' => 4.0,
            ],
            [
                'title'           => 'Google Search PPC Campaign A/B Test for Chaldal Fresh',
                'client_id'       => $cl[1]->id,
                'assigned_to'     => $u['sabbir']->id,
                'assigned_by'     => $u['manager']->id,
                'service_id'      => $srv[2]->id,
                'status'          => 'in_progress',
                'priority'        => 'high',
                'notes'           => 'Optimize negative keyword list for grocery organic vs commercial intent.',
                'deadline'        => now()->addDays(2)->toDateString(),
                'estimated_hours' => 5.0,
            ],
            // Sara (SMM)
            [
                'title'           => 'Finalize Aarong November 30-Day Content Matrix',
                'client_id'       => $cl[0]->id,
                'assigned_to'     => $u['sara']->id,
                'assigned_by'     => $u['admin']->id,
                'service_id'      => $srv[0]->id,
                'status'          => 'in_progress',
                'priority'        => 'high',
                'notes'           => 'Schedule 24 posts and 8 reels in Content Ops calendar.',
                'deadline'        => now()->addDays(3)->toDateString(),
                'estimated_hours' => 6.0,
            ],
            // Joy (Employee)
            [
                'title'           => 'Weekly Retail Competitor Creative Matrix Benchmarking',
                'client_id'       => $cl[0]->id,
                'assigned_to'     => $u['joy']->id,
                'assigned_by'     => $u['sara']->id,
                'service_id'      => null,
                'status'          => 'in_progress',
                'priority'        => 'medium',
                'notes'           => 'Capture ad copies and visual hooks of Yellow, Sailor, and Taaga.',
                'deadline'        => now()->addDays(1)->toDateString(),
                'estimated_hours' => 3.0,
            ],
        ];

        $tsks = [];
        foreach ($tasksData as $td) {
            $tsks[] = Task::create($td);
        }

        // Progress notes
        TaskProgress::create(['task_id' => $tsks[0]->id, 'user_id' => $u['rafi']->id, 'note' => '4 out of 6 carousel layouts completed in Figma.', 'done' => 4, 'total' => 6]);
        TaskProgress::create(['task_id' => $tsks[3]->id, 'user_id' => $u['nafi']->id, 'note' => 'C4D render pass complete. Moving to After Effects.', 'done' => 1, 'total' => 2]);

        // ── 16. Content Posts (Calendar across 6 stages & 5 formats) ──
        $contentData = [
            [
                'title'             => 'Aarong শরৎ সম্ভার ২০২৬ — ঐতিহ্যের সূতা ও আধুনিক কারুকাজ',
                'client_id'         => $cl[0]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'instagram',
                'post_type'         => 'carousel',
                'date'              => now()->subDays(1)->toDateString(),
                'publish_time'      => '18:00',
                'status'            => 'published',
                'caption'           => "প্রকৃতির রঙের সাথে ঐতিহ্যের মেলবন্ধন।\nআড়ং শরৎ সম্ভার ২০২৬ নিয়ে এলো খাঁটি সুতি ও তন্তুর আরামদায়ক পোশাকের সমাহার।\n\n🛍️ নিকটস্থ আড়ং আউটলেটে ঘুরে আসুন অথবা অনলাইনে অর্ডার করুন: aarong.com\n#Aarong #AutumnCollection #BengaliFashion #HandloomHeritage",
                'creative_url'      => 'https://www.figma.com/file/aarong-autumn-published',
                'internal_feedback' => 'Approved by Sara without edits.',
                'client_feedback'   => 'Loved the warmth in the models skin tone.',
            ],
            [
                'title'             => 'Aarong Men’s Khadi Hand-Spun Kurti Showcase',
                'client_id'         => $cl[0]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'facebook',
                'post_type'         => 'design',
                'date'              => now()->toDateString(),
                'publish_time'      => '20:00',
                'status'            => 'approved',
                'caption'           => "পরিমিত আভিজাত্য, নিখুঁত সেলাই।\nপুরুষদের নতুন খাদি কুর্তি কালেকশন এখন আপনার পছন্দের রঙে।\n\n#AarongMen #KhadiCollection #SophisticatedElegance",
                'creative_url'      => 'https://assets.dmsagency.com/creatives/aarong_kurti.png',
                'internal_feedback' => 'Colors verified.',
                'client_feedback'   => 'Approved for scheduled broadcast.',
            ],
            [
                'title'             => 'Behind the Loom: Tangail Weavers Artisan Story (60s Reel)',
                'client_id'         => $cl[0]->id,
                'assigned_to'       => $u['tanvir']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'instagram',
                'post_type'         => 'reel',
                'date'              => now()->addDays(1)->toDateString(),
                'publish_time'      => '19:30',
                'status'            => 'client_approval',
                'caption'           => "প্রতিটি সুতোয় জড়ানো এক একটি ঐতিহ্যবাহী গল্প। টাঙ্গাইলের তাঁত শিল্পীদের নিপুণ হাতের জাদু দেখুন এই বিশেষ ভিডিওতে। 🧵✨\n\n#WeaversOfBengal #AarongCrafts #ArtisanPride",
                'creative_url'      => 'https://assets.dmsagency.com/video/aarong_loom_preview.mp4',
                'internal_feedback' => 'Sara reviewed audio levels: Sound is clean and soothing.',
                'client_feedback'   => 'Awaiting review from Tanzim Rahman.',
            ],
            [
                'title'             => 'Chaldal ১ ঘণ্টায় তাজা শাকসবজি আপনার ঘরে',
                'client_id'         => $cl[1]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'facebook',
                'post_type'         => 'design',
                'date'              => now()->subDays(2)->toDateString(),
                'publish_time'      => '09:00',
                'status'            => 'published',
                'caption'           => "সকালের তাজা বাজার এখন চটজলদি আপনার দ্বারে! 🥦🥕\nচালডাল অ্যাপে অর্ডার করুন আর বুঝে নিন মাত্র ১ ঘণ্টায়।\n\n#Chaldal #FreshVegetables #DhakaGroceries #FastDelivery",
                'creative_url'      => 'https://assets.dmsagency.com/creatives/chaldal_veggies.png',
            ],
            [
                'title'             => 'Chaldal Flash Friday Deals Carousel',
                'client_id'         => $cl[1]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'facebook',
                'post_type'         => 'carousel',
                'date'              => now()->addDays(2)->toDateString(),
                'publish_time'      => '10:00',
                'status'            => 'internal_review',
                'caption'           => "শুক্রবার মানেই বাজারের বিশেষ ছাড়! তেল, ডাল আর মসলায় ফ্ল্যাট ১৫% পর্যন্ত সেভিংস। 🛒✨",
                'creative_url'      => 'https://www.figma.com/file/chaldal-flash-friday',
                'internal_feedback' => 'Sara: Double check discount badges match product SKUs.',
            ],
            [
                'title'             => 'Apex Venturini — Move Beyond City Limits (Reel)',
                'client_id'         => $cl[2]->id,
                'assigned_to'       => $u['tanvir']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'instagram',
                'post_type'         => 'reel',
                'date'              => now()->addDays(3)->toDateString(),
                'publish_time'      => '21:00',
                'status'            => 'client_approval',
                'caption'           => "Engineered for pure street traction and cloud-like cushioning. Step into the all-new Venturini Active series. 👟⚡\n\n#ApexFootwear #VenturiniSneakers #UrbanMovement",
                'creative_url'      => 'https://assets.dmsagency.com/video/apex_sneakers_v1.mp4',
            ],
            [
                'title'             => 'Apex Formal Executive Leather Comfort Collection',
                'client_id'         => $cl[2]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'linkedin',
                'post_type'         => 'design',
                'date'              => now()->addDays(4)->toDateString(),
                'publish_time'      => '11:00',
                'status'            => 'revision',
                'caption'           => "Command the boardroom with effortless comfort. 100% genuine calf leather footwear crafted for leaders.",
                'creative_url'      => 'https://assets.dmsagency.com/creatives/apex_executive.png',
                'client_feedback'   => 'Sabrina: Please brighten the leather brown highlights and make the price badge font bolder.',
            ],
            [
                'title'             => 'Pathao বৃষ্টির দিনে গরম ধোঁয়া ওঠা খিচুড়ি? ফুড আছে তো!',
                'client_id'         => $cl[4]->id,
                'assigned_to'       => $u['tanvir']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'tiktok',
                'post_type'         => 'reel',
                'date'              => now()->subDays(3)->toDateString(),
                'publish_time'      => '13:00',
                'status'            => 'published',
                'caption'           => "বাইরে বৃষ্টি আর ঘরে গরম খিচুড়ি + ইলিশ মাছ ভাজা! পাঠাও ফুডে অর্ডার দিন ৩০ মিনিটের মধ্যে। 🌧️🍛🛵\n#PathaoFood #KhichuriWeather #FoodPornDhaka",
                'creative_url'      => 'https://assets.dmsagency.com/video/pathao_rain_khichuri.mp4',
            ],
            [
                'title'             => 'Pathao Midnight Cravings 25% Off Campaign Post',
                'client_id'         => $cl[4]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'facebook',
                'post_type'         => 'design',
                'date'              => now()->addDays(5)->toDateString(),
                'publish_time'      => '23:00',
                'status'            => 'draft',
                'caption'           => "রাত ১২টায় যখন হঠাৎ পিৎজা কিংবা বার্গারের টান! পাঠাও ফুড মিডনাইট ক্র্যাভিংস। 🍔🍕",
            ],
            [
                'title'             => 'Bengal Meat Angus Ribeye Steak Grilling ASMR Masterclass',
                'client_id'         => $cl[5]->id,
                'assigned_to'       => $u['tanvir']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'instagram',
                'post_type'         => 'reel',
                'date'              => now()->addDays(2)->toDateString(),
                'publish_time'      => '18:30',
                'status'            => 'approved',
                'caption'           => "Listen to the sizzle. 🥩🔥 100% grass-fed organic beef with perfect marbling. Available at Bengal Meat Gourmet stores.\n#BengalMeat #RibeyeSteak #SteakASMR #GourmetFood",
                'creative_url'      => 'https://assets.dmsagency.com/video/bengalmeat_asmr.mp4',
            ],
            [
                'title'             => 'BrainStation 23 Cloud Microservices in Modern Banking',
                'client_id'         => $cl[3]->id,
                'assigned_to'       => $u['sara']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'linkedin',
                'post_type'         => 'copy',
                'date'              => now()->subDays(4)->toDateString(),
                'publish_time'      => '14:00',
                'status'            => 'published',
                'caption'           => "How can tier-1 financial institutions process 50,000 TPS with zero downtime? Discover how BrainStation 23 engineers architected fault-tolerant Kubernetes microservices for regional banks.\n\nRead our engineering whitepaper: brainstation-23.com/cloud\n#FinTech #Microservices #CloudArchitecture #Kubernetes",
            ],
            [
                'title'             => 'BrainStation 23 AI Fraud Detection Framework Infographic',
                'client_id'         => $cl[3]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'linkedin',
                'post_type'         => 'carousel',
                'date'              => now()->addDays(6)->toDateString(),
                'publish_time'      => '15:00',
                'status'            => 'draft',
                'caption'           => "Real-time payment fraud prevention powered by graph neural networks. A 5-slide visual technical breakdown.",
            ],
            [
                'title'             => 'Yellow Lifestyle Autumn Minimalist Essentials Lookbook',
                'client_id'         => $cl[6]->id,
                'assigned_to'       => $u['tanvir']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'instagram',
                'post_type'         => 'video',
                'date'              => now()->addDays(3)->toDateString(),
                'publish_time'      => '19:00',
                'status'            => 'internal_review',
                'caption'           => "Simplicity redefined. Yellow Autumn drop features earthy tones and relaxed silhouettes for every day.",
                'creative_url'      => 'https://assets.dmsagency.com/video/yellow_teaser.mp4',
                'internal_feedback' => 'Sara: Add subtle sound riser before typography reveal.',
            ],
            [
                'title'             => 'Square Health অভিজ্ঞ চিকিৎসকের ভিডিও পরামর্শ ঘরে বসেই',
                'client_id'         => $cl[7]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'facebook',
                'post_type'         => 'design',
                'date'              => now()->addDays(7)->toDateString(),
                'publish_time'      => '11:00',
                'status'            => 'revision',
                'caption'           => "২৪/৭ অভিজ্ঞ বিশেষজ্ঞ ডাক্তারের পরামর্শ এখন হাতের মুঠোয়। স্কয়ার হেলথ অ্যাপ ডাউনলোড করুন। 🩺📱",
                'client_feedback'   => 'Dr. Kamrul: Hospital helpline phone number needs to be placed on top right header.',
            ],
            [
                'title'             => 'Aarong Earth: ১০০% ভেষজ প্রাকৃতিক হেয়ার কেয়ার অয়েল',
                'client_id'         => $cl[0]->id,
                'assigned_to'       => $u['rafi']->id,
                'created_by'        => $u['sara']->id,
                'platform'          => 'facebook',
                'post_type'         => 'design',
                'date'              => now()->addDays(2)->toDateString(),
                'publish_time'      => '17:00',
                'status'            => 'client_approval',
                'caption'           => "ভেষজ উপাদানের সমৃদ্ধ যত্নে আপনার চুলের যত্ন নিন প্রাকৃতিকভাবে। আড়ং আর্থ হারবাল হেয়ার অয়েল। 🌿✨",
                'creative_url'      => 'https://assets.dmsagency.com/creatives/aarong_earth.png',
            ],
        ];

        foreach ($contentData as $cp) {
            ContentPost::create($cp);
        }

        // ── 17. Campaigns (Meta & Google Performance Campaigns) ──
        $campaignsData = [
            [
                'name'            => 'Aarong - Festive Autumn Collection Advantage+ Catalog',
                'client_id'       => $cl[0]->id,
                'platform'        => 'meta',
                'objective'       => 'sales',
                'status'          => 'active',
                'budget'          => 150000,
                'spend'           => 84500,
                'revenue'         => 388700,
                'impressions'     => 420000,
                'clicks'          => 14280,
                'leads_count'     => 0,
                'start_date'      => now()->subDays(14)->toDateString(),
                'end_date'        => now()->addDays(16)->toDateString(),
                'target_audience' => 'Men & Women 22-50, Urban BD (Dhaka, Chittagong, Sylhet), Fashion & Apparel interest',
                'notes'           => 'High-converting Advantage+ dynamic catalog feed. ROAS trending at 4.6x.',
                'ad_account_id'   => 'act_98234710293841',
                'created_by'      => $u['admin']->id,
                'assigned_to'     => $u['sabbir']->id,
            ],
            [
                'name'            => 'Chaldal - Fresh Morning Grocery Daily Delivery Lead & App Install',
                'client_id'       => $cl[1]->id,
                'platform'        => 'meta',
                'objective'       => 'traffic',
                'status'          => 'active',
                'budget'          => 90000,
                'spend'           => 52400,
                'revenue'         => 183400,
                'impressions'     => 310000,
                'clicks'          => 18600,
                'leads_count'     => 420,
                'start_date'      => now()->subDays(10)->toDateString(),
                'end_date'        => now()->addDays(20)->toDateString(),
                'target_audience' => 'Housewives, working professionals 25-55 in Dhaka Metropolitan radius',
                'notes'           => 'App installs and direct web orders for fresh groceries. CPL: 124.76 BDT.',
                'ad_account_id'   => 'act_48102948201948',
                'created_by'      => $u['manager']->id,
                'assigned_to'     => $u['sabbir']->id,
            ],
            [
                'name'            => 'Pathao Food - Midnight Cravings Dynamic Promo Boost',
                'client_id'       => $cl[4]->id,
                'platform'        => 'meta',
                'objective'       => 'engagement',
                'status'          => 'active',
                'budget'          => 110000,
                'spend'           => 78000,
                'revenue'         => 265200,
                'impressions'     => 580000,
                'clicks'          => 26100,
                'leads_count'     => 310,
                'start_date'      => now()->subDays(12)->toDateString(),
                'end_date'        => now()->addDays(18)->toDateString(),
                'target_audience' => 'Youth 18-35 in Dhaka, Chittagong. Active hours 20:00 - 02:00',
                'notes'           => 'Engagement & food delivery promo conversions. ROAS: 3.4x.',
                'ad_account_id'   => 'act_71829304918234',
                'created_by'      => $u['manager']->id,
                'assigned_to'     => $u['sabbir']->id,
            ],
            [
                'name'            => 'Apex - Venturini Sneakers Gen-Z TikTok & Reels Video Push',
                'client_id'       => $cl[2]->id,
                'platform'        => 'tiktok',
                'objective'       => 'awareness',
                'status'          => 'active',
                'budget'          => 60000,
                'spend'           => 28500,
                'revenue'         => 85500,
                'impressions'     => 650000,
                'clicks'          => 19500,
                'leads_count'     => 95,
                'start_date'      => now()->subDays(7)->toDateString(),
                'end_date'        => now()->addDays(14)->toDateString(),
                'target_audience' => 'Ages 16-28 interested in street sneakers, hip-hop culture, lifestyle fashion',
                'notes'           => 'Video view optimization and youth awareness.',
                'ad_account_id'   => 'tt_918204918204',
                'created_by'      => $u['admin']->id,
                'assigned_to'     => $u['sabbir']->id,
            ],
            [
                'name'            => 'BrainStation 23 - Enterprise FinTech Cloud High Intent Search',
                'client_id'       => $cl[3]->id,
                'platform'        => 'google',
                'objective'       => 'leads',
                'status'          => 'active',
                'budget'          => 140000,
                'spend'           => 96200,
                'revenue'         => 481000,
                'impressions'     => 88000,
                'clicks'          => 7480,
                'leads_count'     => 84,
                'start_date'      => now()->subDays(20)->toDateString(),
                'end_date'        => now()->addDays(10)->toDateString(),
                'target_audience' => 'Corporate IT decision makers, CTOs, CIOs searching for enterprise cloud migration',
                'notes'           => 'High value B2B enterprise leads. ROAS: 5.0x.',
                'ad_account_id'   => 'goog_381-920-1928',
                'created_by'      => $u['admin']->id,
                'assigned_to'     => $u['sabbir']->id,
            ],
            [
                'name'            => 'Bengal Meat - Gourmet Marinated BBQ Weekend Flash Sales',
                'client_id'       => $cl[5]->id,
                'platform'        => 'meta',
                'objective'       => 'sales',
                'status'          => 'active',
                'budget'          => 45000,
                'spend'           => 21300,
                'revenue'         => 78800,
                'impressions'     => 165000,
                'clicks'          => 5775,
                'leads_count'     => 110,
                'start_date'      => now()->subDays(5)->toDateString(),
                'end_date'        => now()->addDays(15)->toDateString(),
                'target_audience' => 'Foodies, home cooks, BBQ enthusiasts in Dhanmondi, Gulshan, Banani, Uttara',
                'notes'           => 'Direct WhatsApp orders and e-commerce cart checkouts.',
                'ad_account_id'   => 'act_59102938401928',
                'created_by'      => $u['manager']->id,
                'assigned_to'     => $u['sabbir']->id,
            ],
            [
                'name'            => 'Square Health - 24/7 Specialist Doctor Online Consultation',
                'client_id'       => $cl[7]->id,
                'platform'        => 'google',
                'objective'       => 'leads',
                'status'          => 'paused',
                'budget'          => 80000,
                'spend'           => 44000,
                'revenue'         => 132000,
                'impressions'     => 120000,
                'clicks'          => 8400,
                'leads_count'     => 160,
                'start_date'      => now()->subMonths(2)->toDateString(),
                'end_date'        => now()->subDays(10)->toDateString(),
                'target_audience' => 'Healthcare searchers, online prescription inquiries',
                'notes'           => 'Temporarily paused per client IT server migration request.',
                'ad_account_id'   => 'goog_192-384-9102',
                'created_by'      => $u['admin']->id,
                'assigned_to'     => $u['sabbir']->id,
            ],
            [
                'name'            => 'Yellow Lifestyle - Autumn Minimalist Season Teaser YouTube Video Views',
                'client_id'       => $cl[6]->id,
                'platform'        => 'youtube',
                'objective'       => 'awareness',
                'status'          => 'draft',
                'budget'          => 50000,
                'spend'           => 0,
                'revenue'         => 0,
                'impressions'     => 0,
                'clicks'          => 0,
                'leads_count'     => 0,
                'start_date'      => now()->addDays(5)->toDateString(),
                'end_date'        => now()->addDays(25)->toDateString(),
                'target_audience' => 'Fashion video viewers on YouTube 18-35 in BD',
                'notes'           => 'Draft setup ready for client asset approval.',
                'ad_account_id'   => 'goog_772-102-9481',
                'created_by'      => $u['admin']->id,
                'assigned_to'     => $u['sabbir']->id,
            ],
        ];

        foreach ($campaignsData as $cd) {
            Campaign::create($cd);
        }

        // ── 18. Invoices & Invoice Items ──
        $invoicesData = [
            // Aarong - Last month (Paid)
            [
                'invoice_number' => 'INV-2026-001',
                'client_id'      => $cl[0]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 105000,
                'advance_paid'   => 50000,
                'total'          => 105000,
                'balance'        => 0,
                'status'         => 'paid',
                'notes'          => 'September 2026 full retainer cleared via Standard Chartered Bank transfer.',
                'issued_date'    => now()->subMonths(1)->startOfMonth()->toDateString(),
                'due_date'       => now()->subMonths(1)->startOfMonth()->addDays(15)->toDateString(),
                'items'          => [
                    ['service_name' => 'Social Media Management (SMM 360)', 'qty' => 1, 'unit_price' => 45000, 'total' => 45000],
                    ['service_name' => 'Meta Ads Performance Marketing',    'qty' => 1, 'unit_price' => 25000, 'total' => 25000],
                    ['service_name' => 'Reels & Short-form Video Production','qty' => 8, 'unit_price' => 3500,  'total' => 28000],
                    ['service_name' => 'Creative Design Post Suite',         'qty' => 10,'unit_price' => 700,   'total' => 7000],
                ]
            ],
            // Chaldal - Last month (Paid)
            [
                'invoice_number' => 'INV-2026-002',
                'client_id'      => $cl[1]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 77500,
                'advance_paid'   => 35000,
                'total'          => 77500,
                'balance'        => 0,
                'status'         => 'paid',
                'notes'          => 'Paid via City Bank BEFTN.',
                'issued_date'    => now()->subMonths(1)->toDateString(),
                'due_date'       => now()->subMonths(1)->addDays(14)->toDateString(),
                'items'          => [
                    ['service_name' => 'Meta Ads Performance Marketing',     'qty' => 1, 'unit_price' => 30000, 'total' => 30000],
                    ['service_name' => 'Google Ads PPC Search Optimization', 'qty' => 1, 'unit_price' => 25000, 'total' => 25000],
                    ['service_name' => 'Creative Design Posts (30 Banners)', 'qty' => 30,'unit_price' => 750,   'total' => 22500],
                ]
            ],
            // Apex - (Paid)
            [
                'invoice_number' => 'INV-2026-003',
                'client_id'      => $cl[2]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 102000,
                'advance_paid'   => 40000,
                'total'          => 102000,
                'balance'        => 0,
                'status'         => 'paid',
                'notes'          => 'Full settlement received.',
                'issued_date'    => now()->subDays(20)->toDateString(),
                'due_date'       => now()->subDays(5)->toDateString(),
                'items'          => [
                    ['service_name' => 'Social Media Management (SMM 360)', 'qty' => 1, 'unit_price' => 40000, 'total' => 40000],
                    ['service_name' => 'Motion Graphics & 2D Animations',    'qty' => 6, 'unit_price' => 4500,  'total' => 27000],
                    ['service_name' => 'Reels & Short-form Video Production','qty' => 10,'unit_price' => 3500,  'total' => 35000],
                ]
            ],
            // Aarong - Current Month (Unpaid)
            [
                'invoice_number' => 'INV-2026-004',
                'client_id'      => $cl[0]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 105000,
                'advance_paid'   => 0,
                'total'          => 105000,
                'balance'        => 105000,
                'status'         => 'unpaid',
                'notes'          => 'October 2026 retainer invoice.',
                'issued_date'    => now()->startOfMonth()->toDateString(),
                'due_date'       => now()->addDays(5)->toDateString(),
                'items'          => [
                    ['service_name' => 'Social Media Management (SMM 360)', 'qty' => 1, 'unit_price' => 45000, 'total' => 45000],
                    ['service_name' => 'Meta Ads Performance Marketing',    'qty' => 1, 'unit_price' => 25000, 'total' => 25000],
                    ['service_name' => 'Reels & Short-form Video Production','qty' => 8, 'unit_price' => 3500,  'total' => 28000],
                    ['service_name' => 'Creative Design Post Suite',         'qty' => 10,'unit_price' => 700,   'total' => 7000],
                ]
            ],
            // Chaldal - Current Month (Partial)
            [
                'invoice_number' => 'INV-2026-005',
                'client_id'      => $cl[1]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 77500,
                'advance_paid'   => 30000,
                'total'          => 77500,
                'balance'        => 47500,
                'status'         => 'partial',
                'notes'          => '30,000 BDT advance received on 5th Oct. Balance 47,500 due on 18th Oct.',
                'issued_date'    => now()->subDays(5)->toDateString(),
                'due_date'       => now()->addDays(3)->toDateString(),
                'items'          => [
                    ['service_name' => 'Meta Ads Performance Marketing',     'qty' => 1, 'unit_price' => 30000, 'total' => 30000],
                    ['service_name' => 'Google Ads PPC Search Optimization', 'qty' => 1, 'unit_price' => 25000, 'total' => 25000],
                    ['service_name' => 'Creative Design Posts (30 Banners)', 'qty' => 30,'unit_price' => 750,   'total' => 22500],
                ]
            ],
            // BrainStation 23 - Current Retainer (Unpaid)
            [
                'invoice_number' => 'INV-2026-006',
                'client_id'      => $cl[3]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 69400,
                'advance_paid'   => 0,
                'total'          => 69400,
                'balance'        => 69400,
                'status'         => 'unpaid',
                'notes'          => 'Monthly technical SEO and Google Ads management.',
                'issued_date'    => now()->subDays(2)->toDateString(),
                'due_date'       => now()->addDays(8)->toDateString(),
                'items'          => [
                    ['service_name' => 'SEO & Technical Organic Growth',     'qty' => 1, 'unit_price' => 35000, 'total' => 35000],
                    ['service_name' => 'Google Ads PPC & Search Optimization','qty' => 1, 'unit_price' => 28000, 'total' => 28000],
                    ['service_name' => 'Full-Funnel Content Writing & Copy', 'qty' => 8, 'unit_price' => 800,   'total' => 6400],
                ]
            ],
            // Bengal Meat - (Partial)
            [
                'invoice_number' => 'INV-2026-007',
                'client_id'      => $cl[5]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 60800,
                'advance_paid'   => 25000,
                'total'          => 60800,
                'balance'        => 35800,
                'status'         => 'partial',
                'notes'          => 'Advance 25,000 paid. Remainder due upon delivery of final processed photos.',
                'issued_date'    => now()->subDays(6)->toDateString(),
                'due_date'       => now()->addDays(2)->toDateString(),
                'items'          => [
                    ['service_name' => 'Commercial Product Photography Shoot','qty' => 1, 'unit_price' => 18000, 'total' => 18000],
                    ['service_name' => 'Social Media Management (SMM 360)',   'qty' => 1, 'unit_price' => 30000, 'total' => 30000],
                    ['service_name' => 'Creative Design & Branding Suite',    'qty' => 16,'unit_price' => 800,   'total' => 12800],
                ]
            ],
            // Yellow Lifestyle - Onboarding Retainer (Partial)
            [
                'invoice_number' => 'INV-2026-008',
                'client_id'      => $cl[6]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 155000,
                'advance_paid'   => 45000,
                'total'          => 155000,
                'balance'        => 110000,
                'status'         => 'partial',
                'notes'          => 'Web development and SMM onboarding agreement.',
                'issued_date'    => now()->subDays(8)->toDateString(),
                'due_date'       => now()->addDays(12)->toDateString(),
                'items'          => [
                    ['service_name' => 'E-Commerce Web Design & Development','qty' => 1, 'unit_price' => 75000, 'total' => 75000],
                    ['service_name' => 'Social Media Management (SMM 360)',   'qty' => 1, 'unit_price' => 45000, 'total' => 45000],
                    ['service_name' => 'Reels & Short-form Video Production', 'qty' => 10,'unit_price' => 3500,  'total' => 35000],
                ]
            ],
            // Square Health - Overdue Invoice! (Unpaid)
            [
                'invoice_number' => 'INV-2026-009',
                'client_id'      => $cl[7]->id,
                'created_by'     => $u['admin']->id,
                'subtotal'       => 48000,
                'advance_paid'   => 0,
                'total'          => 48000,
                'balance'        => 48000,
                'status'         => 'unpaid',
                'notes'          => '⚠️ OVERDUE by 12 days. Reminder sent via WhatsApp and Phone.',
                'issued_date'    => now()->subDays(27)->toDateString(),
                'due_date'       => now()->subDays(12)->toDateString(),
                'items'          => [
                    ['service_name' => 'SEO & Technical Organic Growth',     'qty' => 1, 'unit_price' => 28000, 'total' => 28000],
                    ['service_name' => 'Google Ads PPC & Search Optimization','qty' => 1, 'unit_price' => 20000, 'total' => 20000],
                ]
            ],
        ];

        $invs = [];
        foreach ($invoicesData as $id) {
            $items = $id['items'];
            unset($id['items']);
            $inv = Invoice::create($id);
            $invs[] = $inv;

            foreach ($items as $item) {
                InvoiceItem::create(array_merge($item, ['invoice_id' => $inv->id]));
            }
        }

        // ── 19. Reminders Log ──
        Reminder::create([
            'invoice_id'  => $invs[8]->id, // Overdue Square Health
            'template_id' => $tpl[2]->id,
            'sent_by'     => $u['admin']->id,
            'channel'     => 'whatsapp',
            'title'       => 'Urgent Payment Notice — INV-2026-009',
            'message'     => "Dear Dr. Kamrul Hasan,\n\nYour invoice INV-2026-009 of 48,000.00 BDT was due on " . $invs[8]->due_date->format('d M Y') . " and is now 12 days overdue.\n\nKindly clear payment today to avoid service interruption.\n\nDMS Agency",
            'sent_at'     => now()->subDays(1),
        ]);

        Reminder::create([
            'invoice_id'  => $invs[4]->id, // Chaldal Partial
            'template_id' => $tpl[0]->id,
            'sent_by'     => $u['manager']->id,
            'channel'     => 'whatsapp',
            'title'       => 'Reminder — Balance due for INV-2026-005',
            'message'     => "Assalamu Alaikum Wasif Chowdhury,\n\nThis is a friendly reminder that invoice INV-2026-005 for 47,500.00 BDT is due on " . $invs[4]->due_date->format('d M Y') . ".\n\nPlease arrange payment at your convenience.\n\nThank you,\nDMS Agency",
            'sent_at'     => now()->subHours(6),
        ]);

        Reminder::create([
            'invoice_id'  => $invs[5]->id, // BrainStation 23
            'template_id' => $tpl[0]->id,
            'sent_by'     => $u['manager']->id,
            'channel'     => 'email',
            'title'       => 'Upcoming Retainer Payment — INV-2026-006',
            'message'     => "Dear Anisul Huq,\n\nWe have generated invoice INV-2026-006 for 69,400.00 BDT due on " . $invs[5]->due_date->format('d M Y') . ".\n\nAccounts Department\nDMS Agency",
            'sent_at'     => now()->subHours(18),
        ]);

        // ── 20. Targets, Target Items & Target Deals ──
        $currentMonth = now()->format('Y-m'); // e.g. '2026-10'

        // Rahim's Sales Target
        $targetRahim = Target::create([
            'user_id'    => $u['rahim']->id,
            'created_by' => $u['admin']->id,
            'month'      => $currentMonth,
            'label'      => 'Q4 Agency Expansion Target',
        ]);

        $ti1 = TargetItem::create(['target_id' => $targetRahim->id, 'service_name' => 'SMM 360 Full Retainer Packages', 'qty' => 3, 'unit_price' => 45000]);
        $ti2 = TargetItem::create(['target_id' => $targetRahim->id, 'service_name' => 'Performance Ad Management',       'qty' => 4, 'unit_price' => 25000]);
        $ti3 = TargetItem::create(['target_id' => $targetRahim->id, 'service_name' => 'Brand Creative Retainers',        'qty' => 2, 'unit_price' => 35000]);

        TargetDeal::create([
            'target_id'      => $targetRahim->id,
            'target_item_id' => $ti1->id,
            'client_id'      => $cl[0]->id,
            'service_name'   => 'Aarong SMM 360 Contract Renewal',
            'qty'            => 1,
            'amount'         => 45000,
            'date'           => now()->subDays(5)->toDateString(),
        ]);

        TargetDeal::create([
            'target_id'      => $targetRahim->id,
            'target_item_id' => $ti2->id,
            'client_id'      => $cl[1]->id,
            'service_name'   => 'Chaldal Meta Ads Q4 Boost',
            'qty'            => 1,
            'amount'         => 30000,
            'date'           => now()->subDays(8)->toDateString(),
        ]);

        // Karim's Sales Target
        $targetKarim = Target::create([
            'user_id'    => $u['karim']->id,
            'created_by' => $u['admin']->id,
            'month'      => $currentMonth,
            'label'      => 'B2B & Enterprise Accounts Quota',
        ]);

        $ti4 = TargetItem::create(['target_id' => $targetKarim->id, 'service_name' => 'Enterprise SEO Packages', 'qty' => 2, 'unit_price' => 35000]);
        $ti5 = TargetItem::create(['target_id' => $targetKarim->id, 'service_name' => 'Corporate Web Development', 'qty' => 1, 'unit_price' => 75000]);

        TargetDeal::create([
            'target_id'      => $targetKarim->id,
            'target_item_id' => $ti4->id,
            'client_id'      => $cl[3]->id,
            'service_name'   => 'BrainStation 23 SEO Retainer',
            'qty'            => 1,
            'amount'         => 35000,
            'date'           => now()->subDays(4)->toDateString(),
        ]);

        // ── 21. Worklogs (KPI & Staff Output Tracking) ──
        $worklogsData = [
            [
                'user_id'        => $u['rafi']->id,
                'client_id'      => $cl[0]->id,
                'task_id'        => $tsks[0]->id,
                'title'          => 'Designed 4 Vertical Carousels for Aarong Autumn Festival',
                'description'    => 'Extracted 12 high-resolution sarees and kurtis, color graded backgrounds to match warm festive mood, formatted typography.',
                'qty_done'       => 4,
                'qty_total'      => 6,
                'quality_rating' => 5,
                'smm_feedback'   => 'Flawless execution! Typography alignment and brand consistency are top notch.',
                'status'         => 'approved',
                'date'           => now()->subDays(1)->toDateString(),
                'reviewed_by'    => $u['sara']->id,
            ],
            [
                'user_id'        => $u['rafi']->id,
                'client_id'      => $cl[1]->id,
                'task_id'        => $tsks[1]->id,
                'title'          => 'Completed 3 Chaldal Fresh Sale Banners',
                'description'    => 'Designed square 1080x1080, story 1080x1920, and mobile push banner.',
                'qty_done'       => 3,
                'qty_total'      => 3,
                'quality_rating' => 5,
                'smm_feedback'   => 'Delivered well before scheduled deadline.',
                'status'         => 'approved',
                'date'           => now()->subDays(2)->toDateString(),
                'reviewed_by'    => $u['sara']->id,
            ],
            [
                'user_id'        => $u['nafi']->id,
                'client_id'      => $cl[2]->id,
                'task_id'        => $tsks[3]->id,
                'title'          => 'Cinema4D Render Passes for Apex Venturini 3D Logo Reveal',
                'description'    => 'Exported ambient occlusion, depth passes, and specular reflections at 4K 60fps.',
                'qty_done'       => 1,
                'qty_total'      => 2,
                'quality_rating' => 5,
                'smm_feedback'   => 'Render quality is cinematic. Ready for AE composite.',
                'status'         => 'approved',
                'date'           => now()->subDays(1)->toDateString(),
                'reviewed_by'    => $u['manager']->id,
            ],
            [
                'user_id'        => $u['nafi']->id,
                'client_id'      => $cl[4]->id,
                'task_id'        => $tsks[4]->id,
                'title'          => 'Pathao Courier Kinetic Typography Promo Animation',
                'description'    => 'Completed motion graphics in After Effects, timed to upbeat electronic music track.',
                'qty_done'       => 1,
                'qty_total'      => 1,
                'quality_rating' => 4,
                'smm_feedback'   => 'Great energy! Minor easing adjustment done on outro logo.',
                'status'         => 'approved',
                'date'           => now()->subDays(2)->toDateString(),
                'reviewed_by'    => $u['sara']->id,
            ],
            [
                'user_id'        => $u['tanvir']->id,
                'client_id'      => $cl[0]->id,
                'task_id'        => $tsks[5]->id,
                'title'          => 'Rough & Color Graded Cut for Tangail Loom Documentary',
                'description'    => 'Synchronized 4K Sony FX3 footage, applied custom organic film LUT, balanced flute background audio.',
                'qty_done'       => 1,
                'qty_total'      => 1,
                'quality_rating' => 5,
                'smm_feedback'   => 'Heart-touching storytelling. Client will love this.',
                'status'         => 'approved',
                'date'           => now()->subDays(1)->toDateString(),
                'reviewed_by'    => $u['sara']->id,
            ],
            [
                'user_id'        => $u['tariq']->id,
                'client_id'      => $cl[3]->id,
                'task_id'        => $tsks[7]->id,
                'title'          => 'Resolved LCP Delays & Configured FAQ JSON-LD Schema',
                'description'    => 'LCP improved from 3.8s to 1.4s on mobile. Verified rich snippets in Google Search Console.',
                'qty_done'       => 2,
                'qty_total'      => 3,
                'quality_rating' => 5,
                'smm_feedback'   => 'Impressive speed score improvement on PageSpeed Insights.',
                'status'         => 'approved',
                'date'           => now()->subDays(2)->toDateString(),
                'reviewed_by'    => $u['admin']->id,
            ],
            [
                'user_id'        => $u['sabbir']->id,
                'client_id'      => $cl[0]->id,
                'task_id'        => $tsks[9]->id,
                'title'          => 'Configured Advantage+ Catalog Shopping Campaign & Conversions API',
                'description'    => 'Mapped 800+ product catalog items, setup deduplicated server events via Stape Cloud GTM.',
                'qty_done'       => 1,
                'qty_total'      => 1,
                'quality_rating' => 5,
                'smm_feedback'   => 'Event match quality reached 9.4/10. Outstanding.',
                'status'         => 'approved',
                'date'           => now()->subDays(3)->toDateString(),
                'reviewed_by'    => $u['manager']->id,
            ],
            [
                'user_id'        => $u['joy']->id,
                'client_id'      => $cl[0]->id,
                'task_id'        => $tsks[12]->id,
                'title'          => 'Audited Top 5 Apparel Competitor Facebook Ad Creative Hooks',
                'description'    => 'Compiled 25 ad screenshots, headline copies, and CTA comparisons in Notion matrix.',
                'qty_done'       => 1,
                'qty_total'      => 1,
                'quality_rating' => 4,
                'smm_feedback'   => 'Very useful competitive insight for our upcoming sprint.',
                'status'         => 'approved',
                'date'           => now()->subDays(1)->toDateString(),
                'reviewed_by'    => $u['sara']->id,
            ],
        ];

        foreach ($worklogsData as $wl) {
            Worklog::create($wl);
        }

        // ── 22. Notifications ──
        $notifs = [
            [
                'user_id'       => null,
                'icon'          => 'triangle-exclamation',
                'bg_color'      => '#fee2e2',
                'message'       => '🚨 Urgent Alert: Invoice #INV-2026-009 for Square Health (48,000 BDT) is 12 days overdue!',
                'is_admin_only' => true,
                'is_read'       => false,
            ],
            [
                'user_id'       => null,
                'icon'          => 'trophy',
                'bg_color'      => '#dcfce7',
                'message'       => '🎉 High-Value Sale Closed: Shwapno Super Shop (95,000 BDT) approved by Admin!',
                'is_admin_only' => false,
                'is_read'       => false,
            ],
            [
                'user_id'       => $u['sara']->id,
                'icon'          => 'circle-check',
                'bg_color'      => '#dbeafe',
                'message'       => '📋 New Team Requisition: Pathao Campaign Retainer approved and allocated to your SMM desk.',
                'is_admin_only' => false,
                'is_read'       => false,
            ],
            [
                'user_id'       => $u['sabbir']->id,
                'icon'          => 'chart-line',
                'bg_color'      => '#fef3c7',
                'message'       => '📈 Performance Milestone: Aarong Advantage+ Campaign reached 4.6x ROAS with 388k BDT generated revenue.',
                'is_admin_only' => false,
                'is_read'       => false,
            ],
            [
                'user_id'       => $u['tanvir']->id,
                'icon'          => 'thumbs-up',
                'bg_color'      => '#dcfce7',
                'message'       => '🎬 Client Approved Video: Apex Venturini Reel approved without revision by Sabrina.',
                'is_admin_only' => false,
                'is_read'       => true,
            ],
            [
                'user_id'       => $u['rahim']->id,
                'icon'          => 'calendar',
                'bg_color'      => '#e0e7ff',
                'message'       => '📅 Upcoming Meeting: Walton Digi-Tech contract finalization in Conference Room tomorrow at 2:00 PM.',
                'is_admin_only' => false,
                'is_read'       => false,
            ],
            [
                'user_id'       => $u['rafi']->id,
                'icon'          => 'pen-to-square',
                'bg_color'      => '#ffedd5',
                'message'       => '✍️ Revision Requested: Yellow Lifestyle requested font highlight adjustment on feed banner.',
                'is_admin_only' => false,
                'is_read'       => false,
            ],
        ];

        foreach ($notifs as $n) {
            Notification::create($n);
        }

        // ── 23. Personal My Work Items ──
        MyWorkItem::create([
            'user_id'   => $u['rahim']->id,
            'title'     => 'Send Walton final SLA contract draft before 2 PM meeting',
            'category'  => 'Sales Ops',
            'priority'  => 'high',
            'status'    => 'in_progress',
            'due_date'  => now()->addDays(1)->toDateString(),
            'notes'     => 'Coordinate with Farhan Manager on corporate warranty clause.',
        ]);

        MyWorkItem::create([
            'user_id'   => $u['sara']->id,
            'title'     => 'Review and approve draft reels submitted by Tanvir',
            'category'  => 'Review',
            'priority'  => 'high',
            'status'    => 'pending',
            'due_date'  => now()->toDateString(),
            'notes'     => 'Aarong handloom documentary cut.',
        ]);

        MyWorkItem::create([
            'user_id'   => $u['rafi']->id,
            'title'     => 'Export final high-res PNGs for Aarong Autumn Carousels',
            'category'  => 'Creative',
            'priority'  => 'medium',
            'status'    => 'in_progress',
            'due_date'  => now()->addDays(1)->toDateString(),
        ]);

        MyWorkItem::create([
            'user_id'   => $u['sabbir']->id,
            'title'     => 'Check Meta CAPI deduplication match rates in Event Manager',
            'category'  => 'Ad Tech',
            'priority'  => 'high',
            'status'    => 'done',
            'due_date'  => now()->subDays(1)->toDateString(),
            'completed_at' => now()->subDays(1),
        ]);

        // ── 24. Attendances (Daily Punctuality & Absence Records) ──
        $attendanceDays = [
            '2026-10-01', '2026-10-02', '2026-10-03', '2026-10-04',
            '2026-10-06', '2026-10-07', '2026-10-08', '2026-10-09',
            '2026-10-10', '2026-10-11', '2026-10-12', '2026-10-13'
        ];

        foreach ($u as $username => $userObj) {
            foreach ($attendanceDays as $idx => $dateStr) {
                $status = 'present';
                $checkIn = '09:30:00';
                $checkOut = '18:30:00';
                $notes = null;

                // Occasional late or leave records
                if ($username === 'rafi' && $idx === 2) {
                    $status = 'late';
                    $checkIn = '10:15:00';
                    $notes = 'Traffic congestion at Banani bridge';
                } elseif ($username === 'tanvir' && $idx === 4) {
                    $status = 'leave';
                    $checkIn = null;
                    $checkOut = null;
                    $notes = 'Approved personal leave day';
                } elseif ($username === 'karim' && $idx === 6) {
                    $status = 'absent';
                    $checkIn = null;
                    $checkOut = null;
                    $notes = 'Unscheduled absence';
                } elseif ($username === 'joy' && $idx === 7) {
                    $status = 'half_day';
                    $checkIn = '09:30:00';
                    $checkOut = '14:00:00';
                    $notes = 'Half day for medical consultation';
                }

                Attendance::create([
                    'user_id'   => $userObj->id,
                    'date'      => $dateStr,
                    'status'    => $status,
                    'check_in'  => $checkIn,
                    'check_out' => $checkOut,
                    'notes'     => $notes,
                ]);
            }
        }

        // ── 25. Employee Salaries & Bonuses (Payroll & KPI Rewards) ──
        $payrollData = [
            'admin'   => ['base' => 120000, 'bonus' => 20000, 'deductions' => 0, 'status' => 'paid',    'method' => 'bank_transfer', 'notes' => 'Executive founder drawing'],
            'manager' => ['base' => 85000,  'bonus' => 15000, 'deductions' => 0, 'status' => 'paid',    'method' => 'bank_transfer', 'notes' => 'Q4 agency operations bonus'],
            'rahim'   => ['base' => 60000,  'bonus' => 18000, 'deductions' => 0, 'status' => 'paid',    'method' => 'bank_transfer', 'notes' => 'Commission on Shwapno closed sale'],
            'sara'    => ['base' => 52000,  'bonus' => 10000, 'deductions' => 0, 'status' => 'paid',    'method' => 'bank_transfer', 'notes' => 'Aarong festive sprint incentive'],
            'rafi'    => ['base' => 48000,  'bonus' => 8000,  'deductions' => 0, 'status' => 'pending', 'method' => 'bank_transfer', 'notes' => 'Creative quality milestone'],
            'nafi'    => ['base' => 50000,  'bonus' => 7500,  'deductions' => 0, 'status' => 'pending', 'method' => 'bank_transfer', 'notes' => 'Apex 3D animation milestone'],
            'tanvir'  => ['base' => 45000,  'bonus' => 6000,  'deductions' => 0, 'status' => 'pending', 'method' => 'bank_transfer', 'notes' => '4K Reel cuts delivery bonus'],
            'tariq'   => ['base' => 65000,  'bonus' => 10000, 'deductions' => 0, 'status' => 'pending', 'method' => 'bank_transfer', 'notes' => 'Core Web Vitals speed reward'],
            'sabbir'  => ['base' => 58000,  'bonus' => 12000, 'deductions' => 0, 'status' => 'pending', 'method' => 'bank_transfer', 'notes' => '4.6x ROAS performance incentive'],
            'joy'     => ['base' => 32000,  'bonus' => 3000,  'deductions' => 0, 'status' => 'pending', 'method' => 'bkash',         'notes' => 'Competitor intelligence reward'],
            'karim'   => ['base' => 55000,  'bonus' => 8000,  'deductions' => 0, 'status' => 'pending', 'method' => 'bank_transfer', 'notes' => 'BrainStation deal renewal bonus'],
        ];

        foreach ($payrollData as $username => $p) {
            $userObj = $u[$username];
            $net = $p['base'] + $p['bonus'] - $p['deductions'];
            EmployeeSalary::create([
                'user_id'        => $userObj->id,
                'month'          => '2026-10',
                'base_salary'    => $p['base'],
                'bonus'          => $p['bonus'],
                'deductions'     => $p['deductions'],
                'net_salary'     => $net,
                'status'         => $p['status'],
                'paid_at'        => $p['status'] === 'paid' ? now()->subDays(3)->toDateString() : null,
                'payment_method' => $p['method'],
                'notes'          => $p['notes'],
            ]);
        }

        echo "\n🎉 SUCCESS: All DMS CRM tables seeded with agency-grade realistic data!\n";
        echo "=========================================================================\n";
        echo "  • Users: 11 accounts (10 shortcut login tiers + 1 sales peer)\n";
        echo "  • Clients: 8 top Bangladeshi brands (Aarong, Chaldal, Apex, etc.)\n";
        echo "  • Leads: 8 pipeline leads across all stages with Quotation & Tax math\n";
        echo "  • Campaigns: 8 live Meta/Google campaigns with CTR, CPL, ROAS metrics\n";
        echo "  • Invoices: 9 invoices (Paid, Partial, Unpaid, Overdue) with Items\n";
        echo "  • Content Posts: 15 posts across 6 workflow stages and 5 formats\n";
        echo "  • Tasks: 13 Kanban deliverables with JSON Checklists and Attachments\n";
        echo "  • Meetings: 5 client/lead meetings with Attendees\n";
        echo "  • Followups: 5 multi-channel logs (Call, WhatsApp, Visit)\n";
        echo "  • Requisitions: 2 approved/pending won-deal requisitions with team\n";
        echo "  • Worklogs: 8 staff productivity entries with 1-5 star ratings\n";
        echo "  • Reminders: 3 WhatsApp & Email payment reminders\n";
        echo "  • Targets: Monthly sales quotas and deals achieved\n";
        echo "  • Expenses: 8 agency operating transactions\n";
        echo "  • Notifications: 7 system event notifications\n";
        echo "=========================================================================\n";
        echo "Shortcut Logins: admin / admin123 | manager / manager123 | rahim / rahim123\n";
        echo "                 sara / sara123  | rafi / rafi123       | nafi / nafi123\n";
        echo "                 tanvir / tanvir123 | tariq / tariq123   | sabbir / sabbir123\n";
        echo "                 joy / joy123    | karim / karim123\n\n";
    }
}
