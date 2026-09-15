<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureControlMiddleware
{
    public function handle(Request $request, Closure $next, string $feature): mixed
    {
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Unauthenticated'], 401)
                : redirect('/login');
        }

        $user = Auth::user();

        if (!$user->active) {
            Auth::logout();
            return $request->expectsJson()
                ? response()->json(['error' => 'Account deactivated'], 403)
                : redirect('/login')->withErrors(['username' => 'Your account has been deactivated.']);
        }

        if (!$user->canAccess($feature)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => "Access Restricted: You do not have permission for feature '{$feature}'."], 403);
            }

            return redirect()->route('dashboard')->with('error', "Access Restricted: Your role ('" . ucfirst($user->role) . "') does not have access to section '{$feature}'.");
        }

        return $next($request);
    }
}
