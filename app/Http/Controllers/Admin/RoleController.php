<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['users', 'permissions'])->get();
        $permissions = Permission::all();
        return view('dashboard.roles.index', compact('roles', 'permissions'));
    }

    public function show(Role $role)
    {
        $role->load(['users', 'permissions']);
        $allPermissions = Permission::all()->groupBy('module');
        return view('dashboard.roles.show', compact('role', 'allPermissions'));
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::all()->groupBy('module');
        return view('dashboard.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('dashboard.roles.index')
            ->with('success', 'Role updated successfully.');
    }
}
