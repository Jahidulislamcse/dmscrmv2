<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, File};
use App\Models\{User, LeadStage, Client, Lead, Service, Invoice, Expense};

class SettingsController extends Controller
{
    private function getSettingsPath(): string
    {
        return storage_path('app/settings.json');
    }

    private function getAgencySettings(): array
    {
        $path = $this->getSettingsPath();
        $defaults = [
            'agency_name' => config('app.name', 'DMS Creative Agency'),
            'agency_email' => env('COMPANY_EMAIL', 'info@dmssoftware.agency'),
            'agency_phone' => env('COMPANY_PHONE', '+880-171-0000000'),
            'agency_address' => env('COMPANY_ADDRESS', 'Dhaka, Bangladesh'),
            'agency_currency' => '৳',
            'tax_id' => 'BIN-992019481',
        ];

        if (File::exists($path)) {
            $json = json_decode(File::get($path), true);
            if (is_array($json)) {
                return array_merge($defaults, $json);
            }
        }

        return $defaults;
    }

    public function index()
    {
        $settings = $this->getAgencySettings();
        $user = Auth::user();
        
        // System Info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP CLI / XAMPP / cPanel',
            'memory_limit' => ini_get('memory_limit'),
            'timezone' => config('app.timezone'),
        ];

        $leadStages = LeadStage::orderBy('order', 'asc')->get();

        return view('settings.index', compact('settings', 'user', 'systemInfo', 'leadStages'));
    }

    public function updateAgency(Request $request)
    {
        $validated = $request->validate([
            'agency_name' => 'required|string|max:255',
            'agency_email' => 'required|email|max:255',
            'agency_phone' => 'required|string|max:100',
            'agency_address' => 'required|string|max:500',
            'agency_currency' => 'required|string|max:10',
            'tax_id' => 'nullable|string|max:100',
        ]);

        $path = $this->getSettingsPath();
        $directory = dirname($path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::put($path, json_encode($validated, JSON_PRETTY_PRINT));

        return redirect()->route('settings.index')->with('success', 'Agency settings updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'color' => 'nullable|string|max:50',
        ]);

        $user->update($validated);

        return redirect()->route('settings.index')->with('success', 'Account profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The provided current password does not match.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings.index')->with('success', 'Password changed successfully!');
    }

    public function exportBackup()
    {
        $data = [
            'exported_at' => now()->toDateTimeString(),
            'exported_by' => Auth::user()->name ?? 'Admin',
            'agency_settings' => $this->getAgencySettings(),
            'users' => User::all(['id', 'name', 'username', 'email', 'role', 'active']),
            'clients' => Client::all(),
            'services' => Service::all(),
            'leads' => Lead::with(['requirements', 'timelines'])->get(),
            'invoices' => Invoice::with(['items'])->get(),
            'expenses' => Expense::all(),
        ];

        $filename = 'dmscrm_backup_' . date('Y-m-d_H-i-s') . '.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }
}
