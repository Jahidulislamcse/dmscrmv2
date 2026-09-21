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
            'team'         => 'Team & Feature Controls',
            'services'     => 'Services Catalog',
            'invoices'     => 'Invoices & Billing',
            'reminders'    => 'Payment Reminders Hub',
            'expenses'     => 'Expenses Tracker',
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
            'owner' => ['dashboard', 'my_tasks', 'crm', 'meetings', 'requisitions', 'clients', 'tasks', 'team', 'services', 'invoices', 'reminders', 'expenses', 'settings'],
            'admin' => ['dashboard', 'my_tasks', 'crm', 'meetings', 'requisitions', 'clients', 'tasks', 'team', 'services', 'invoices', 'reminders', 'expenses'],
            'sales' => ['dashboard', 'my_tasks', 'crm', 'meetings', 'requisitions', 'clients', 'tasks', 'reminders'],
            'smm' => ['dashboard', 'my_tasks', 'clients', 'meetings', 'tasks', 'reminders'],
            'designer' => ['dashboard', 'my_tasks', 'meetings', 'tasks'],
            'motion' => ['dashboard', 'my_tasks', 'meetings', 'tasks'],
            'video' => ['dashboard', 'my_tasks', 'meetings', 'tasks'],
            'seo' => ['dashboard', 'my_tasks', 'meetings', 'tasks'],
            'mediabuyer' => ['dashboard', 'my_tasks', 'meetings', 'tasks'],
            'employee' => ['dashboard', 'my_tasks', 'meetings', 'tasks'],
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
                    // Merge defaults for any missing roles
                    foreach ($defaults as $role => $mods) {
                        if (!isset($json[$role])) {
                            $json[$role] = $mods;
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
