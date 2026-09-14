<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, File};
use App\Models\{User, LeadStage};

class SettingsController extends Controller
{
    private function getSettingsPath(): string
    {
        return storage_path('app/settings.json');
    }

    public static function getAgencySettings(): array
    {
        $defaults = [
            'agency_name' => config('app.name', 'DMS Creative Agency'),
            'agency_email' => env('COMPANY_EMAIL', 'info@dmssoftware.agency'),
            'agency_phone' => env('COMPANY_PHONE', '+880-171-0000000'),
            'agency_address' => env('COMPANY_ADDRESS', 'Dhaka, Bangladesh'),
            'agency_currency' => '৳',
            'tax_id' => 'BIN-992019481',
            'agency_logo' => null,
        ];

        try {
            $path = storage_path('app/settings.json');
            if (File::exists($path)) {
                $json = json_decode(File::get($path), true);
                if (is_array($json)) {
                    return array_merge($defaults, $json);
                }
            }
        } catch (\Throwable $e) {
            // Fallback to default settings
        }

        return $defaults;
    }

    public function index()
    {
        $settings = self::getAgencySettings();
        $user = Auth::user();
        $leadStages = LeadStage::orderBy('order', 'asc')->get();

        return view('settings.index', compact('settings', 'user', 'leadStages'));
    }

    public function updateAgency(Request $request)
    {
        try {
            $validated = $request->validate([
                'agency_name' => 'required|string|max:255',
                'agency_email' => 'required|email|max:255',
                'agency_phone' => 'required|string|max:100',
                'agency_address' => 'required|string|max:500',
                'agency_currency' => 'required|string|max:10',
                'tax_id' => 'nullable|string|max:100',
            ]);

            $currentSettings = self::getAgencySettings();
            $data = [
                'agency_name' => $validated['agency_name'],
                'agency_email' => $validated['agency_email'],
                'agency_phone' => $validated['agency_phone'],
                'agency_address' => $validated['agency_address'],
                'agency_currency' => $validated['agency_currency'],
                'tax_id' => $validated['tax_id'] ?? null,
                'agency_logo' => $currentSettings['agency_logo'] ?? null,
            ];

            // File upload handling without requiring php_fileinfo extension
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                if ($file->isValid()) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

                    if (!in_array($ext, $allowed)) {
                        return redirect()->back()->with('error', 'Invalid logo image format. Allowed formats: PNG, JPG, JPEG, SVG, WEBP, GIF.')->withInput();
                    }

                    $filename = 'logo_' . time() . '.' . $ext;

                    // Ensure upload directories exist across possible cPanel web roots
                    $targetDirs = array_filter(array_unique([
                        public_path('uploads'),
                        base_path('public/uploads'),
                        base_path('uploads'),
                        isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/uploads' : null,
                    ]));

                    $primaryDir = public_path('uploads');

                    foreach ($targetDirs as $dir) {
                        if (!File::exists($dir)) {
                            @File::makeDirectory($dir, 0775, true, true);
                        }
                    }

                    $file->move($primaryDir, $filename);
                    $sourceFile = $primaryDir . '/' . $filename;

                    // Sync copy to all other target web root paths for cPanel compatibility
                    foreach ($targetDirs as $dir) {
                        if ($dir !== $primaryDir && File::exists($sourceFile)) {
                            @File::copy($sourceFile, $dir . '/' . $filename);
                        }
                    }

                    $data['agency_logo'] = 'uploads/' . $filename;
                }
            }

            $path = $this->getSettingsPath();
            $directory = dirname($path);

            if (!File::exists($directory)) {
                @File::makeDirectory($directory, 0775, true, true);
            }

            File::put($path, json_encode($data, JSON_PRETTY_PRINT));

            return redirect()->route('settings.index')->with('success', 'Agency settings & site logo updated successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error saving settings: ' . $e->getMessage())->withInput();
        }
    }

    public function updateProfile(Request $request)
    {
        try {
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
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error updating profile: ' . $e->getMessage())->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        try {
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
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error changing password: ' . $e->getMessage());
        }
    }
}
