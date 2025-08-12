<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class PermissionManagementController extends Controller
{
    public function index()
    {
        $permissions = DB::table('permissions as p')
            ->join('roles as r', 'p.role_id', '=', 'r.id')
            ->select('p.*', 'r.name as role_name')
            ->get();

        $roles = Role::where('status', 1)->get();

        return view('user_management.permissions', [
            'permissions' => $permissions,
            'roles' => $roles,
            'pageTitle' => 'Permissions'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'menu' => 'required|string',
            'permission' => 'required|string',
        ]);

        DB::table('permissions')->insert([
            'role_id' => $request->role_id,
            'menu_name' => $request->menu,
            'permission' => $request->permission,
            'status' => 1,
            'time_created' => now()
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission added.');
    }

    public function edit($id)
    {
        $permission = DB::table('permissions')->where('id', $id)->first();
        $roles = Role::where('status', 1)->get();

        return view('user_management.edit_permission', [
            'permission' => $permission,
            'roles' => $roles,
            'pageTitle' => 'Edit Permission'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_name' => 'required|string',
            'permission' => 'required|string',
        ]);

        DB::table('permissions')->where('id', $id)->update([
            'menu_name' => $request->menu_name,
            'permission' => $request->permission,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated.');
    }

    public function destroy($id)
    {
        DB::table('permissions')->where('id', $id)->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted.');
    }

    public function disable($id)
    {
        DB::table('permissions')->where('id', $id)->update(['status' => 0]);

        return redirect()->route('permissions.index')->with('success', 'Permission disabled.');
    }
}

