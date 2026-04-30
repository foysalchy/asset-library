<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function index()
    {
        $roles = Role::withCount('users', 'permissions')->orderBy('created_at')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('label')->get()->groupBy('group');
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:roles,name', 'regex:/^[a-z_]+$/'],
            'label'       => ['required', 'string', 'max:100'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name'  => $request->name,
            'label' => $request->label,
            'is_super_admin' => false,
        ]);

        if ($request->filled('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        $this->activityLog->log('created', $role, "Created role: {$role->label}");

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        if ($role->is_super_admin) {
            return redirect()->route('roles.index')->with('error', 'Super admin role cannot be edited.');
        }

        $permissions    = Permission::orderBy('group')->orderBy('label')->get()->groupBy('group');
        $selectedPerms  = $role->permissions->pluck('id')->toArray();
        $users          = User::orderBy('name')->get();
        $assignedUsers  = $role->users->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'selectedPerms', 'users', 'assignedUsers'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->is_super_admin) {
            return redirect()->route('roles.index')->with('error', 'Super admin role cannot be edited.');
        }

        $request->validate([
            'label'         => ['required', 'string', 'max:100'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
            'users'         => ['nullable', 'array'],
            'users.*'       => ['exists:users,id'],
        ]);

        $role->update(['label' => $request->label]);
        $role->permissions()->sync($request->permissions ?? []);
        $role->users()->sync($request->users ?? []);

        $this->activityLog->log('updated', $role, "Updated role: {$role->label}");

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->is_super_admin) {
            return redirect()->route('roles.index')->with('error', 'Super admin role cannot be deleted.');
        }

        $this->activityLog->log('deleted', $role, "Deleted role: {$role->label}");
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}