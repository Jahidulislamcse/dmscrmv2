<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Service, LeadStage, Client, ClientService, Lead, ExpenseCategory, CustomStatus, ReminderTemplate};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──
        $users = [
            ['name'=>'Mehedi Hasan',  'username'=>'admin',  'password'=>'admin123',  'role'=>'owner',    'color'=>'#f59e0b'],
            ['name'=>'Rahim Uddin',   'username'=>'rahim',  'password'=>'rahim123',  'role'=>'sales',    'color'=>'#3b82f6'],
            ['name'=>'Karim Sheikh',  'username'=>'karim',  'password'=>'karim123',  'role'=>'sales',    'color'=>'#8b5cf6'],
            ['name'=>'Rafi Ahmed',    'username'=>'rafi',   'password'=>'rafi123',   'role'=>'designer', 'color'=>'#ec4899'],
            ['name'=>'Nafi Islam',    'username'=>'nafi',   'password'=>'nafi123',   'role'=>'motion',   'color'=>'#14b8a6'],
            ['name'=>'Sara Begum',    'username'=>'sara',   'password'=>'sara123',   'role'=>'smm',      'color'=>'#f97316'],
            ['name'=>'Tariq Hassan',  'username'=>'tariq',  'password'=>'tariq123',  'role'=>'seo',      'color'=>'#10b981'],
        ];

        $createdUsers = [];
        foreach ($users as $u) {
            $createdUsers[$u['username']] = User::create([
                'name'     => $u['name'],
                'username' => $u['username'],
                'password' => $u['password'],
                'role'     => $u['role'],
                'color'    => $u['color'],
                'active'   => true,
            ]);
        }

        // ── Services ──
        $services = [
            ['name'=>'Social Media Management', 'base_price'=>8000,  'unit'=>'month'],
            ['name'=>'Social Media Design',     'base_price'=>500,   'unit'=>'post'],
            ['name'=>'SEO Package',             'base_price'=>5000,  'unit'=>'month'],
            ['name'=>'Website Design & Dev',    'base_price'=>25000, 'unit'=>'project'],
            ['name'=>'Motion Graphics',         'base_price'=>2000,  'unit'=>'video'],
            ['name'=>'Content Writing',         'base_price'=>300,   'unit'=>'article'],
            ['name'=>'Paid Ads Management',     'base_price'=>5000,  'unit'=>'month'],
            ['name'=>'Photography',             'base_price'=>3000,  'unit'=>'session'],
            ['name'=>'Video Production',        'base_price'=>8000,  'unit'=>'video'],
        ];

        $createdServices = [];
        foreach ($services as $s) {
            $createdServices[] = Service::create(array_merge($s, ['active' => true]));
        }

        // ── Lead Stages ──
        $stages = [
            ['name'=>'New Lead',    'color'=>'#94a3b8', 'order'=>1],
            ['name'=>'Contacted',   'color'=>'#3b82f6', 'order'=>2],
            ['name'=>'Meeting Done','color'=>'#8b5cf6', 'order'=>3],
            ['name'=>'Proposal',    'color'=>'#f59e0b', 'order'=>4],
            ['name'=>'Negotiation', 'color'=>'#f97316', 'order'=>5],
            ['name'=>'Won',         'color'=>'#10b981', 'order'=>6, 'is_won'=>true],
            ['name'=>'Lost',        'color'=>'#ef4444', 'order'=>7, 'is_lost'=>true],
        ];
        $createdStages = [];
        foreach ($stages as $s) {
            $createdStages[] = LeadStage::create($s);
        }

        // ── Expense Categories ──
        $cats = [
            ['name'=>'Office Rent',    'color'=>'#3b82f6'],
            ['name'=>'Utilities',      'color'=>'#8b5cf6'],
            ['name'=>'Software',       'color'=>'#ec4899'],
            ['name'=>'Marketing',      'color'=>'#f59e0b'],
            ['name'=>'Salaries',       'color'=>'#10b981'],
            ['name'=>'Equipment',      'color'=>'#f97316'],
            ['name'=>'Miscellaneous',  'color'=>'#64748b'],
        ];
        foreach ($cats as $c) ExpenseCategory::create($c);

        // ── Reminder Templates ──
        ReminderTemplate::create(['name'=>'Friendly Reminder', 'type'=>'whatsapp', 'days_before'=>7,
            'body'=>"Assalamu Alaikum {client_name},\n\nThis is a friendly reminder that invoice {invoice_number} for {balance_due} is due on {due_date}.\n\nPlease arrange payment at your earliest convenience.\n\nThank you!\n{company_name}"]);
        ReminderTemplate::create(['name'=>'Urgent Reminder', 'type'=>'whatsapp', 'days_before'=>1,
            'body'=>"Dear {client_name},\n\nYour invoice {invoice_number} of {balance_due} was due on {due_date}.\n\nKindly clear the payment today to avoid service interruption.\n\n{company_name}"]);

        // ── Default Custom Statuses (global) ──
        $defaultStatuses = [
            ['name'=>'Pending',     'color'=>'#94a3b8', 'order'=>1, 'core_status'=>'pending',             'is_default'=>true],
            ['name'=>'In Progress', 'color'=>'#3b82f6', 'order'=>2, 'core_status'=>'in_progress',         'is_default'=>true],
            ['name'=>'Review',      'color'=>'#8b5cf6', 'order'=>3, 'core_status'=>'done_pending_review',  'is_default'=>true],
            ['name'=>'Done',        'color'=>'#10b981', 'order'=>4, 'core_status'=>'done',                 'is_default'=>true],
        ];
        foreach ($defaultStatuses as $s) CustomStatus::create($s);

        // ── Sample Clients ──
        $client1 = Client::create([
            'name'          => 'Ahmed Rahman',
            'company'       => 'Rahman Enterprise',
            'phone'         => '+880-171-1234567',
            'email'         => 'ahmed@rahman.com',
            'location'      => 'Dhaka',
            'assigned_smm'  => $createdUsers['sara']->id,
            'assigned_sales'=> $createdUsers['rahim']->id,
            'status'        => 'active',
            'billing_cycle' => 'monthly',
            'advance'       => 10000,
            'onboarded_at'  => now()->subMonths(3)->toDateString(),
        ]);

        ClientService::create(['client_id'=>$client1->id, 'service_id'=>$createdServices[0]->id, 'price'=>8000, 'qty'=>1]);
        ClientService::create(['client_id'=>$client1->id, 'service_id'=>$createdServices[1]->id, 'price'=>500,  'qty'=>20]);

        $client2 = Client::create([
            'name'          => 'Mita Fashion',
            'company'       => 'Mita Fashion House',
            'phone'         => '+880-181-7654321',
            'email'         => 'mita@fashion.com',
            'location'      => 'Chittagong',
            'assigned_smm'  => $createdUsers['sara']->id,
            'assigned_sales'=> $createdUsers['karim']->id,
            'status'        => 'active',
            'billing_cycle' => 'monthly',
            'advance'       => 5000,
            'onboarded_at'  => now()->subMonths(1)->toDateString(),
        ]);
        ClientService::create(['client_id'=>$client2->id, 'service_id'=>$createdServices[0]->id, 'price'=>6000, 'qty'=>1]);

        // ── Sample Leads ──
        Lead::create([
            'name'=>'Rubel Islam','phone'=>'+880-191-9999999','company'=>'Rubel Shop',
            'source'=>'Facebook','stage_id'=>$createdStages[1]->id,
            'assigned_to'=>$createdUsers['rahim']->id,'created_by'=>$createdUsers['rahim']->id,
            'budget'=>8000,'notes'=>'Interested in SM package','next_followup'=>now()->addDays(3)->toDateString(),
        ]);
        Lead::create([
            'name'=>'Sadia Akter','phone'=>'+880-171-8888888','company'=>'Sadia Biz',
            'source'=>'Referral','stage_id'=>$createdStages[3]->id,
            'assigned_to'=>$createdUsers['karim']->id,'created_by'=>$createdUsers['admin']->id,
            'budget'=>15000,'notes'=>'Meeting done, proposal sent','next_followup'=>now()->addDays(5)->toDateString(),
        ]);

        echo "✅ Database seeded successfully!\n";
        echo "Login: admin / admin123\n";
        echo "Sales: rahim / rahim123\n";
        echo "SMM:   sara / sara123\n";
        echo "Designer: rafi / rafi123\n";
    }
}
