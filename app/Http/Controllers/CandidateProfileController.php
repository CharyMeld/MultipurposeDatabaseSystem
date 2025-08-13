<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\MedicalSchool;
use App\Models\Institution;
use App\Models\PostgraduateTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Good for debugging

class CandidateProfileController extends Controller
{
    public function overview($id)
    {
        $candidate = Candidate::with(['faculty', 'medicalSchools', 'institutions', 'postgraduateTrainings'])
            ->findOrFail($id);

        return new CandidateOverviewResource($candidate);
    }

    
   /**
     * Return JSON data for API calls (used by Vue fetching).
     */
   
    public function showJson(Candidate $candidate)
    {
        $candidate->load([
            'medicalSchools',
            'institutions',
            'postgraduateTrainings',
            'faculty',
            'applications',
            'results'
        ]);

        return response()->json([
            'candidate' => $candidate,
            'facultyName' => optional($candidate->faculty)->name,
            'subSpeciality' => $candidate->sub_speciality ?? '',
            'fullName' => trim(($candidate->surname ?? '') . ' ' . ($candidate->other_name ?? '')),
            'docFields' => [],
            'medicalSchools' => $candidate->medicalSchools,
            'institutions' => $candidate->institutions,
            'postgraduates' => $candidate->postgraduateTrainings,
            'applications' => $candidate->applications ?? [],
            'results' => $candidate->results ?? [],
        ]);
    }

    // Blade page
    public function show(Candidate $candidate)
    {
        $candidate->load([
            'medicalSchools',
            'institutions',
            'postgraduateTrainings',
            'faculty',
            'applications',
            'results'
        ]);

        $facultyName = optional($candidate->faculty)->name;
        $subSpeciality = $candidate->sub_speciality ?? '';
        $fullName = trim(($candidate->surname ?? '') . ' ' . ($candidate->other_name ?? ''));
        $docFields = [];

        return view('candidates.profile', compact(
            'candidate', 'facultyName', 'subSpeciality', 'fullName', 'docFields'
        ))->with([
            'medicalSchools' => $candidate->medicalSchools,
            'institutions' => $candidate->institutions,
            'postgraduates' => $candidate->postgraduateTrainings,
            'applications' => $candidate->applications ?? [],
            'results' => $candidate->results ?? [],
        ]);
    }





    /**
     * Save a new medical school and return it as JSON.
     */
    public function saveMedicalSchool(Request $request)
    {
        $data = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        // Create the new record
        $medicalSchool = MedicalSchool::create($data);

        // Return the newly created model as a JSON response
        return response()->json($medicalSchool, 201); // 201 Created
    }

    /**
     * Update an existing medical school and return a success response.
     */
    public function updateMedicalSchool(Request $request)
    {
        // Validate the incoming request data, including the ID
        $data = $request->validate([
            'id' => 'required|exists:medical_schools,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $medicalSchool = MedicalSchool::findOrFail($data['id']);
        $medicalSchool->update($data);

        return response()->json(['status' => 'success', 'message' => 'Medical school updated successfully.']);
    }

    /**
     * Delete a medical school and return a success response.
     */
    public function deleteMedicalSchool(Request $request)
    {
        $data = $request->validate(['id' => 'required|exists:medical_schools,id']);
        MedicalSchool::findOrFail($data['id'])->delete();
        
        return response()->json(['status' => 'success', 'message' => 'Medical school deleted successfully.']);
    }

    // --- Institution Methods (Corrected) ---

     public function saveInstitution(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'name' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'institution_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|boolean'
        ]);

        $institution = \App\Models\Institution::create([
            'candidate_id' => $request->candidate_id,
            'name' => $request->name,
            'degree' => $request->degree,
            'institution_type' => $request->institution_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return response()->json([
            'success' => true,
            'data' => $institution
        ]);
    }

    
    // Note: You will need to add routes for update/delete institution if you build that functionality.

    // --- Postgraduate Training Methods (Corrected) ---

    public function getPostgraduates(Candidate $candidate)
    {
        $postgraduates = PostgraduateTraining::where('candidate_id', $candidate->id)
            ->select('id', 'post_training_school as name') // map column to 'name' for frontend
            ->get();

        return response()->json($postgraduates);
    }

    public function savePostgraduate(Candidate $candidate, Request $request)
    {
        $data = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'name' => 'required|string|max:255', // frontend sends 'name'
            'degree' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        // Map 'name' to 'post_training_school' for database
        $postgrad = PostgraduateTraining::create([
            'candidate_id' => $data['candidate_id'],
            'post_training_school' => $data['name'],
            'degree' => $data['degree'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
        ]);

        return response()->json(['status' => 'success', 'data' => [
            'id' => $postgrad->id,
            'name' => $postgrad->post_training_school // return as 'name' for frontend
        ]], 201);
    }

    
    // Note: You will need to add routes for update/delete postgrad if you build that functionality.
}

