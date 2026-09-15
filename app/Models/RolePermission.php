<?php

namespace App\Models;

use Illuminate\Support\Facades\File;

class RolePermission
{
    public static function allFeatures(): array
    {
        return [
            'dashboard'    => 'Overview Dashboard',
            'crm'          => 'CRM Leads Pipeline',
            'meetings'     => 'Meetings Schedule',
            'requisitions' => 'Requisitions Hub',
            'clients'      => 'Clients Management',
            'tasks'        => 'Task & Deliverables Board',
            'team'         => 'Team & Feature Controls',
            'services'     => 'Services Catalog',
            'invoices'     => 'Invoices & Billing',
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
            'owner' => ['dashboard', 'crm', 'meetings', 'requisitions', 'clients', 'tasks', 'team', 'services', 'invoices', 'expenses', 'settings'],
            'admin' => ['dashboard', 'crm', 'meetings', 'requisitions', 'clients', 'tasks', 'team', 'services', 'invoices', 'expenses'],
            'sales' => ['dashboard', 'crm', 'meetings', 'requisitions', 'clients', 'tasks'],
            'smm' => ['dashboard', 'clients', 'meetings', 'tasks'],
            'designer' => ['dashboard', 'meetings', 'tasks'],
            'motion' => ['dashboard', 'meetings', 'tasks'],
            'video' => ['dashboard', 'meetings', 'tasks'],
            'seo' => ['dashboard', 'meetings', 'tasks'],
            'mediabuyer' => ['dashboard', 'meetings', 'tasks'],
            'employee' => ['dashboard', 'meetings', 'tasks'],
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
