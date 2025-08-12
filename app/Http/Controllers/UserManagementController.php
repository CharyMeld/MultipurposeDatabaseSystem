<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;


class UserManagementController extends Controller
{
    public function index()
    {
        $users = DB::table('users as u')
            ->leftJoin('roles as r', 'u.role_id', '=', 'r.id')
            ->select('u.*', 'r.name as role_name')
            ->get();

        $roles = Role::where('status', 1)->get();

        return view('users.index', [
            'users' => $users,
            'roles' => $roles,
            'pageTitle' => 'Users'
        ]);
    }

    public function create()
    {
        $roles = Role::where('status', 1)->get();

        return view('user_management.add_user', [
            'roles' => $roles,
            'pageTitle' => 'Add User'
        ]);
    }

    public function store(Request $request)
    {
        // Log form data (you already did this)
        \Log::info($request->all());

        // Validate the input
        $validated = $request->validate([
            'login_name'     => 'required|unique:users,login_name',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:5',
            'firstname'      => 'required',
            'lastname'       => 'required',
            'middlename'     => 'nullable',
            'gender'         => 'required',
            'marital_status' => 'required',
            'role_id'        => 'required|exists:roles,id',
        ]);

        // Create and save user
        $user = new User();
        $user->login_name     = $validated['login_name'];
        $user->email          = $validated['email'];
        $user->password       = Hash::make($validated['password']); // Make sure to import Hash
        $user->firstname      = $validated['firstname'];
        $user->lastname       = $validated['lastname'];
        $user->middlename     = $validated['middlename'] ?? null;
        $user->gender         = $validated['gender'];
        $user->marital_status = $validated['marital_status'];
        $user->role_id        = $validated['role_id'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }

}

