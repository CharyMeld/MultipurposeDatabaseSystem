<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Session::has('user_id')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

         $user = DB::table('users')->where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::regenerate();

            Session::put('user_id', $user->id);
            Session::put('email', $user->email);
            Session::put('full_name', trim("{$user->firstname} {$user->middlename} {$user->lastname}"));

            return redirect()->route('dashboard');
        }


                
        // Authentication failed
        return redirect()->route('login')->with('error', 'Invalid email or password.');
    }

    /**
     * Handle logout request.
     */
   public function logout()
    {
        Session::flush();
        Session::regenerateToken();

        return redirect('/login');
    }

}

