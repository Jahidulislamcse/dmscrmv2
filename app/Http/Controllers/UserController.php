<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, RolePermission};
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied. You do not have permission to manage team members & role feature controls.');
        }

        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(12);

        $roles = RolePermission::allRoles();
        $features = RolePermission::allFeatures();
        $permissions = RolePermission::getPermissions();

        return view('team.index', compact('users', 'roles', 'features', 'permissions'));
    }

    public function create()
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        $roles = RolePermission::allRoles();
        return view('team.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:' . implode(',', array_keys(RolePermission::allRoles())),
            'color' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'color' => $validated['color'] ?? '#64748b',
            'active' => true,
        ]);

        return redirect()->route('team.index')->with('success', "Team member '{$user->name}' registered successfully with role '" . ucfirst($user->role) . "'!");
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        $roles = RolePermission::allRoles();
        return view('team.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:' . implode(',', array_keys(RolePermission::allRoles())),
            'color' => 'nullable|string|max:20',
            'active' => 'required|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'],
            'color' => $validated['color'] ?? '#64748b',
            'active' => $validated['active'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('team.index')->with('success', "Team member '{$user->name}' updated successfully!");
    }

    public function updatePermissions(Request $request)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied. Only authorized admins can manage role feature controls.');
        }

        $rawPermissions = $request->input('permissions', []);
        $allRoles = array_keys(RolePermission::allRoles());
        $allFeatures = array_keys(RolePermission::allFeatures());

        $matrix = [];
        foreach ($allRoles as $role) {
            if ($role === 'owner') {
                // Owner always has all features
                $matrix['owner'] = $allFeatures;
                continue;
            }

            $allowed = isset($rawPermissions[$role]) && is_array($rawPermissions[$role])
                ? array_values(array_intersect($rawPermissions[$role], $allFeatures))
                : ['dashboard'];

            // Ensure dashboard is always available
            if (!in_array('dashboard', $allowed)) {
                $allowed[] = 'dashboard';
            }

            $matrix[$role] = array_values(array_unique($allowed));
        }

        RolePermission::savePermissions($matrix);

        return redirect()->route('team.index')->with('success', 'Role Feature Control Permissions saved and enforced across the system successfully!');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', "You cannot deactivate yourself!");
        }

        $user->update(['active' => false]);
        return redirect()->route('team.index')->with('success', "Team member '{$user->name}' deactivated!");
    }
}
