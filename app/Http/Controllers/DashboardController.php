<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Candidate;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
     public function index()
    {
        $totalCandidates = DB::table('candidates')->count();

        $facultyStats = DB::table('candidates')
            ->join('faculty', 'candidates.faculty_id', '=', 'faculty.id')
            ->select('faculty.name as faculty_name', DB::raw('COUNT(*) as total'))
            ->groupBy('faculty.name')
            ->get();

        return view('dashboard', [
            'totalCandidates' => $totalCandidates,
            'facultyStats' => $facultyStats,
        ]);
    }


}




