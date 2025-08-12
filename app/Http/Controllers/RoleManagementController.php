<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::orderByDesc('created_at')->get();

        return view('user_management.roles', [
            'roles' => $roles,
            'editRole' => null,
            'permissionRole' => null,
            'assigned' => [],
            'allPermissions' => [
                'view_users' => 'View Users',
                'edit_users' => 'Edit Users',
                'delete_users' => 'Delete Users',
                'view_roles' => 'View Roles',
                'edit_roles' => 'Edit Roles',
            ],
            'pageTitle' => 'Roles'
        ]);
    }

    public function create()
    {
       return view('user_management.roles', [
            'roles' => Role::all(),
            'editRole' => null // Fixed: was referencing undefined $role variable
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 1,
            'created_at' => now(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Role added.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $roles = Role::orderByDesc('created_at')->get();

        // FIXED: Return the same view with editRole data to show modal
        return view('user_management.roles', [
            'roles' => $roles,
            'editRole' => $role, // This will trigger the modal to show
            'permissionRole' => null,
            'assigned' => [],
            'allPermissions' => [
                'view_users' => 'View Users',
                'edit_users' => 'Edit Users',
                'delete_users' => 'Delete Users',
                'view_roles' => 'View Roles',
                'edit_roles' => 'Edit Roles',
            ],
            'pageTitle' => 'Edit Role'
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->only('name', 'description'));

        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }

    public function destroy($id)
    {
        Role::destroy($id);

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }

    public function disable($id)
    {
        Role::where('id', $id)->update(['status' => 0]);

        return redirect()->route('roles.index')->with('success', 'Role disabled.');
    }

    // NEW: Method to handle permissions modal
    public function permissions($id)
    {
        $role = Role::findOrFail($id);
        $roles = Role::orderByDesc('created_at')->get();
        
        // Get assigned permissions for this role (you'll need to implement this based on your permission system)
        $assigned = []; // Replace with actual logic to get assigned permissions

        return view('user_management.roles', [
            'roles' => $roles,
            'editRole' => null,
            'permissionRole' => $role, // This will trigger the permissions modal to show
            'assigned' => $assigned,
            'allPermissions' => [
                'view_users' => 'View Users',
                'edit_users' => 'Edit Users',
                'delete_users' => 'Delete Users',
                'view_roles' => 'View Roles',
                'edit_roles' => 'Edit Roles',
            ],
            'pageTitle' => 'Role Permissions'
        ]);
    }

    // NEW: Method to handle permission updates
    public function updatePermissions(Request $request)
    {
        $roleId = $request->input('role_id');
        $permissions = $request->input('permissions', []);
        
        // Implement your permission update logic here
        // This depends on how you store role permissions in your database
        
        return redirect()->route('roles.index')->with('success', 'Permissions updated.');
    }
}

