<?php

namespace App\Models;

use Illuminate\Support\Facades\File;

class RolePermission
{
    public static function allFeatures(): array
    {
        return [
            'dashboard'    => 'Overview Dashboard',
            'my_tasks'     => 'My Assigned Tasks',
            'crm'          => 'CRM Leads Pipeline',
            'meetings'     => 'Meetings Schedule',
            'requisitions' => 'Requisitions Hub',
            'clients'      => 'Clients Management',
            'tasks'        => 'Task & Deliverables Board',
            'calendar'     => 'Content Ops & Calendar',
            'campaigns'    => 'Campaign Management (Meta/Google)',
            'team'         => 'Team & Feature Controls',
            'services'     => 'Services Catalog',
            'invoices'     => 'Invoices & Billing',
            'reminders'    => 'Payment Reminders Hub',
            'followups'    => 'Follow-ups Management',
            'expenses'     => 'Expenses Tracker',
            'reports'      => 'Monthly Client Reports',
            'settings'     => 'System Settings',
        ];
    }

    public static function allRoles(): array
    {
        return [
            'owner'      => 'Owner / Super Admin',
            'admin'      => 'Agency Manager / Admin',
            'sales'      => 'Sales Executive',
            'smm'        => 'Social Media Manager',
            'designer'   => 'Senior Graphic Designer',
            'motion'     => 'Motion Designer',
            'video'      => 'Video Editor',
            'seo'        => 'SEO & Web Developer',
            'mediabuyer' => 'Media Buyer / Ads Manager',
            'employee'   => 'General Team Member',
        ];
    }

    public static function defaultPermissions(): array
    {
        return [
            'owner' => ['dashboard', 'my_tasks', 'crm', 'meetings', 'followups', 'requisitions', 'clients', 'tasks', 'calendar', 'campaigns', 'team', 'services', 'invoices', 'reminders', 'expenses', 'reports', 'settings'],
            'admin' => ['dashboard', 'my_tasks', 'crm', 'meetings', 'followups', 'requisitions', 'clients', 'tasks', 'calendar', 'campaigns', 'team', 'services', 'invoices', 'reminders', 'expenses', 'reports'],
            'sales' => ['dashboard', 'my_tasks', 'crm', 'meetings', 'followups', 'requisitions', 'clients', 'tasks', 'calendar', 'campaigns', 'reminders', 'reports'],
            'smm' => ['dashboard', 'my_tasks', 'clients', 'meetings', 'followups', 'tasks', 'calendar', 'campaigns', 'reminders', 'reports'],
            'designer' => ['dashboard', 'my_tasks', 'meetings', 'tasks', 'calendar'],
            'motion' => ['dashboard', 'my_tasks', 'meetings', 'tasks', 'calendar'],
            'video' => ['dashboard', 'my_tasks', 'meetings', 'tasks', 'calendar'],
            'seo' => ['dashboard', 'my_tasks', 'meetings', 'tasks', 'calendar'],
            'mediabuyer' => ['dashboard', 'my_tasks', 'meetings', 'tasks', 'calendar', 'campaigns', 'reports'],
            'employee' => ['dashboard', 'my_tasks', 'meetings', 'tasks', 'calendar'],
        ];
    }

    public static function getPermissions(): array
    {
        $defaults = self::defaultPermissions();
        $path = storage_path('app/role_permissions.json');

        try {
            if (File::exists($path)) {
                $json = json_decode(File::get($path), true);
                if (is_array($json)) {
                    // Merge defaults for any missing roles or essential administrative roles
                    foreach ($defaults as $role => $mods) {
                        if (!isset($json[$role])) {
                            $json[$role] = $mods;
                        } elseif (in_array($role, ['owner', 'admin'])) {
                            $json[$role] = array_values(array_unique(array_merge($json[$role], $mods)));
                        }
                    }
                    return $json;
                }
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return $defaults;
    }

    public static function savePermissions(array $matrix): bool
    {
        $path = storage_path('app/role_permissions.json');
        try {
            $dir = dirname($path);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            File::put($path, json_encode($matrix, JSON_PRETTY_PRINT));
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
