<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Notification;

class AuthController extends Controller
{
    public function showLogin() {
        if (Auth::check()) return redirect('/dashboard');
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials['username'] = strtolower($credentials['username']);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !$user->active || !Hash::check($credentials['password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 422);
            }

            return back()->withErrors(['username' => 'Invalid credentials'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'user' => $user,
                'csrf_token' => csrf_token(),
            ]);
        }

        return redirect('/dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Logged out',
                'csrf_token' => csrf_token(),
            ]);
        }

        return redirect('/login');
    }

    public function me() {
        $user = Auth::user();
        if (!$user || !$user->active) {
            Auth::logout();

            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notifCount = Notification::where(function($q) use($user) {
            $q->where('user_id', $user->id)->orWhereNull('user_id');
        })->where('is_read', false)
          ->when(!$user->isOwner(), fn($q) => $q->where('is_admin_only', false))
          ->count();

        return response()->json([
            'user'         => $user,
            'notif_count'  => $notifCount,
            'can_access'   => $this->getUserPermissions($user),
        ]);
    }

    private function getUserPermissions(User $user): array {
        $modules = [
            'dashboard','clients','invoices','reminders','expenses','crm','meetings',
            'followups','targets','my_target','my_work','tasks','my_tasks','calendar',
            'worklogs','reports','team','services','requisitions','workload','dayplan',
            'gcal','agent','settings'
        ];
        return array_values(array_filter($modules, fn($m) => $user->canAccess($m)));
    }
}
