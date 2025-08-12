<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WelcomeController extends Controller
{
    public function __construct()
    {
        // Ensure only authenticated users can access this controller
        $this->middleware('auth');
    }

    public function index()
    {
        $username = auth()->user()->name; // Or use 'username' if available
        return view('welcome', ['username' => $username]);
    }
}

