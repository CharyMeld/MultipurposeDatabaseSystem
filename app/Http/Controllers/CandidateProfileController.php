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
     * Update the specified candidate.
     * Handles both standard form submissions and AJAX requests.
     */
    public function update(Request $request, Candidate $candidate)
    {
        // Use the existing validation logic. It's good.
        $validated = $this->validateCandidateData($request, $candidate->id);

        try {
            DB::beginTransaction();
            
            $candidate->update($validated);
            
            DB::commit();
            
            Log::info('Candidate updated successfully', ['candidate_id' => $candidate->id]);

            // HERE IS THE FIX: Check if the request is AJAX
            if ($request->wantsJson() || $request->ajax()) {
                // If it's an AJAX request from your Vue component, return a JSON response.
                return response()->json([
                    'status' => 'success',
                    'message' => 'Candidate updated successfully!',
                    'candidate' => $candidate->fresh() // Send back the updated data
                ]);
            }
            
            // For traditional form submissions, return the redirect as before.
            return redirect()
                ->route('candidates.show', $candidate)
                ->with('success', 'Candidate updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update candidate', [
                'candidate_id' => $candidate->id,
                'error' => $e->getMessage()
            ]);
            
            // Also handle AJAX error responses
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update candidate. Please try again.'
                ], 500); // 500 Internal Server Error
            }
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update candidate. Please try again.');
        }
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
        $data = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'name' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'institution_type' => 'required|string|max:255',
            'status' => 'nullable|boolean'
        ]);

        $institution = Institution::create($data);
        return response()->json($institution, 201);
    }
    
    // Note: You will need to add routes for update/delete institution if you build that functionality.

    // --- Postgraduate Training Methods (Corrected) ---

    public function savePostgraduateTraining(Request $request)
    {
        $data = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'post_training_school' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $postgrad = PostgraduateTraining::create($data);
        return response()->json(['status' => 'success', 'data' => $postgrad], 201);
    }
    
    // Note: You will need to add routes for update/delete postgrad if you build that functionality.
}

