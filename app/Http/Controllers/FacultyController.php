<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculty = Faculty::orderByDesc('id')->get();
        return view('faculty.index', compact('faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['status'] = $validated['status'] === 'active' ? 1 : 0;

        Faculty::create($validated);

        return redirect()->route('faculty.index')->with('success', 'Faculty added successfully.');
    }

    public function edit($id)
    {
        $facultyToEdit = Faculty::findOrFail($id);
        $faculty = Faculty::orderByDesc('id')->get();

        return view('faculty.index', compact('faculties', 'facultyToEdit'));
    }

    public function update(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['status'] = $validated['status'] === 'active' ? 1 : 0;

        $faculty->update($validated);

        return redirect()->route('faculty.index')->with('success', 'Faculty updated successfully.');
    }

    public function destroy($id)
    {
        Faculty::destroy($id);

        return redirect()->route('faculty.index')->with('success', 'Faculty deleted successfully.');
    }

    public function toggle($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->status = $faculty->status ? 0 : 1;
        $faculty->save();

        return redirect()->route('faculty.index')->with('success', 'Faculty status updated.');
    }
}

