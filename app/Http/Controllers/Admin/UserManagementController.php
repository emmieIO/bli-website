<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // Middleware will be handled in routes
    }

    /**
     * Display users management interface
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with([
            'message' => "Role '{$request->role}' assigned to {$user->name} successfully",
            'type' => 'success'
        ]);
    }

    /**
     * Remove role from user
     */
    public function removeRole(User $user, $role)
    {
        $user->removeRole($role);

        return redirect()->back()->with([
            'message' => "Role '{$role}' removed from {$user->name} successfully",
            'type' => 'success'
        ]);
    }

    /**
     * Display roles management interface
     */
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[0]; // Group by prefix (course, category, etc.)
        });

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with([
            'message' => "Permissions updated for role '{$role->name}' successfully",
            'type' => 'success'
        ]);
    }

    /**
     * Get user statistics
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'admins' => User::role('admin')->count(),
            'instructors' => User::role('instructor')->count(),
            'students' => User::role('student')->count(),
            'users_without_roles' => User::doesntHave('roles')->count()
        ];

        return response()->json($stats);
    }

    /**
     * Toggle permission for a role
     */
    public function togglePermission(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
            'action' => 'required|in:add,remove'
        ]);

        $role = Role::findByName($request->role);
        
        if ($request->action === 'add') {
            $role->givePermissionTo($request->permission);
            $message = "Permission '{$request->permission}' granted to {$role->name} role";
        } else {
            $role->revokePermissionTo($request->permission);
            $message = "Permission '{$request->permission}' removed from {$role->name} role";
        }

        return redirect()->back()->with([
            'message' => $message,
            'type' => 'success'
        ]);
    }
}