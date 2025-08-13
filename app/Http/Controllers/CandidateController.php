<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ZipArchive;



class CandidateController extends Controller
{
    /**
     * West African countries for validation
     */
    private const WEST_AFRICAN_COUNTRIES = [
        'Benin', 'Burkina Faso', 'Cape Verde', "Côte d'Ivoire", 'Gambia',
        'Ghana', 'Guinea', 'Guinea-Bissau', 'Liberia', 'Mali',
        'Niger', 'Nigeria', 'Senegal', 'Sierra Leone', 'Togo'
    ];

    /**
     * Display a listing of candidates with search and filters
     */
    public function index(Request $request)
    {
        $query = Candidate::with('faculty');

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('surname', 'like', "%{$search}%")
                  ->orWhere('other_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Faculty filter
        if ($facultyFilter = $request->input('faculty_filter')) {
            $query->where('faculty_id', $facultyFilter);
        }

        // Entry mode filter
        if ($entryModeFilter = $request->input('entry_mode_filter')) {
            $query->where('entry_mode', $entryModeFilter);
        }

        // Gender filter
        if ($genderFilter = $request->input('gender_filter')) {
            $query->where('gender', $genderFilter);
        }

        $candidates = $query->orderBy('created_at', 'desc')->paginate(20);
        $faculty = Faculty::orderBy('name')->get();

        return view('candidates.index', compact(
            'candidates', 
            'faculty', 
            'search', 
            'facultyFilter', 
            'entryModeFilter'
        ));
    }

    /**
     * Show the form for creating a new candidate
     */
    public function create()
    {
        $westAfricanCountries = self::WEST_AFRICAN_COUNTRIES;
        $faculty = Faculty::orderBy('name')->get();

        return view('candidates.add_candidate', compact('westAfricanCountries', 'faculty'));
    }

    /**
     * Store a newly created candidate
     */
    public function store(Request $request)
    {
        $validated = $this->validateCandidateData($request);

        try {
            DB::beginTransaction();
            
            $candidate = Candidate::create($validated);
            
            DB::commit();
            
            Log::info('Candidate created successfully', ['candidate_id' => $candidate->id]);
            
            return redirect()
                ->route('candidates.show', $candidate)
                ->with('success', 'Candidate created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create candidate', ['error' => $e->getMessage()]);
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create candidate. Please try again.');
        }
    }

    /**
     * Display the specified candidate
     */
    public function show(Candidate $candidate)
    {
        $candidate->load('faculty');
        
        return view('candidates.index', compact('candidate'));
    }

    /**
     * Show the form for editing the specified candidate
     */
    public function edit(Candidate $candidate)
    {
        $faculty = Faculty::orderBy('name')->get();
        
        return view('candidates.edit', compact('candidate', 'faculty'));
    }


 /**
     * Update the specified candidate.
     * Handles both standard form submissions and AJAX requests.
     */
    public function update(Request $request, Candidate $candidate)
    {
        //  Validate all fillable fields from Candidate model
        $validated = $request->validate([
            'registration_number' => 'nullable|string|max:255',
            'cert_number' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'maiden_name' => 'nullable|string|max:255',
            'entry_mode' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'change_of_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'postal_address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female',
            'nationality' => 'nullable|string|max:255',
            'fellowship_type' => 'nullable|string|max:50',
            'faculty_id' => 'nullable|integer|exists:faculty,id',
            'sub_speciality' => 'nullable|string|max:255',
            'full_registration_date' => 'nullable|date',
            'nysc_discharge_or_exemption' => 'nullable|string|max:255',
            'prefered_exam_center' => 'nullable|string|max:255',
            'accredited_training_program' => 'nullable|string|max:255',
            'post_registration_appointment' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $candidate->update($validated);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Candidate updated successfully!',
                    'candidate' => $candidate->fresh()
                ]);
            }

            return redirect()
                ->route('candidates.show', $candidate)
                ->with('success', 'Candidate updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update candidate. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Failed to update candidate. Please try again.');
        }
    }

    
   
    /**
     * Remove the specified candidate
     */
    public function destroy(Candidate $candidate)
    {
        try {
            DB::beginTransaction();
            
            $candidateId = $candidate->id;
            $candidate->delete();
            
            DB::commit();
            
            Log::info('Candidate deleted successfully', ['candidate_id' => $candidateId]);
            
            return redirect()
                ->route('candidates.index')
                ->with('success', 'Candidate deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete candidate', [
                'candidate_id' => $candidate->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to delete candidate. Please try again.');
        }
    }



    /**
     * Show bulk upload form
     */
    public function showBulkUploadForm()
    {
        return view('candidates.bulk_upload');
    }

    /**
     * Process bulk upload and show preview
     */
    public function processBulkUpload(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:10240', // 10MB max
        ]);

        try {
            $result = $this->processUploadedZip($request->file('zip_file'));
            
            if (!empty($result['preview'])) {
                Session::put('bulk_candidate_data', $result['preview']);
            }

            return view('candidates.bulk_upload_preview', [
                'preview' => $result['preview'],
                'customErrors' => $result['errors'],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Bulk upload processing failed', ['error' => $e->getMessage()]);
            
            return back()->with('error', 'Failed to process upload: ' . $e->getMessage());
        }
    }

    /**
     * Save bulk preview data to database
     */
    public function saveBulkPreview(Request $request)
    {
        if (!$request->has('confirm_save') || !Session::has('bulk_candidate_data')) {
            return redirect()
                ->route('candidates.bulk-upload')
                ->with('error', 'Invalid request or session expired.');
        }

        $candidates = Session::get('bulk_candidate_data');
        $successCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            foreach ($candidates as $candidateData) {
                try {
                    // Validate each candidate data using the Form Request
                    $candidateRequest = new \App\Http\Requests\CandidateRequest();
                    $rules = $candidateRequest->rules();
                    
                    // For bulk import, 'registration_number' uniqueness is checked manually
                    // and 'dob' can be 'nullable' if not provided in CSV
                    $rules['registration_number'] = array_filter($rules['registration_number'], function($rule) {
                        return !($rule instanceof Rule && $rule->type === 'unique');
                    });
                    $rules['dob'] = array_filter($rules['dob'], function($rule) {
                        return $rule !== 'required';
                    });
                    array_unshift($rules['dob'], 'nullable'); // Add nullable back if it was removed

                    $validator = Validator::make($candidateData, $rules);
                    
                    if ($validator->fails()) {
                        $errors[] = "Registration " . ($candidateData['registration_number'] ?? 'N/A') . ": " . 
                                   implode(', ', $validator->errors()->all());
                        continue;
                    }

                    // Check for duplicates again (in case data changed since preview)
                    if (Candidate::where('registration_number', $candidateData['registration_number'])->exists()) {
                        $errors[] = "Duplicate registration number: {$candidateData['registration_number']}";
                        continue;
                    }

                    Candidate::create($this->prepareCandidateData($candidateData));
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Registration " . ($candidateData['registration_number'] ?? 'N/A') . ": " . $e->getMessage();
                }
            }

            DB::commit();
            Session::forget('bulk_candidate_data');

            Log::info('Bulk candidate import completed', [
                'success_count' => $successCount,
                'error_count' => count($errors)
            ]);

            $message = "Successfully imported {$successCount} candidate(s).";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " failed.";
            }

            return redirect()
                ->route('candidates.index')
                ->with('success', $message)
                ->with('import_errors', $errors);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk import transaction failed', ['error' => $e->getMessage()]);
            
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Process uploaded ZIP file and extract CSV data
     */
    private function processUploadedZip($zipFile)
    {
        $tempDir = storage_path('app/temp/' . uniqid());
        
        try {
            // Create temp directory
            if (!Storage::makeDirectory('temp/' . basename($tempDir))) {
                throw new \Exception('Failed to create temporary directory');
            }

            // Move uploaded file
            $zipPath = $tempDir . '/upload.zip';
            $zipFile->move(dirname($zipPath), basename($zipPath));

            // Extract ZIP
            $extractPath = $tempDir . '/extracted';
            Storage::makeDirectory('temp/' . basename($extractPath));
            
            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== TRUE) {
                throw new \Exception('Failed to open ZIP file');
            }
            
            $zip->extractTo(storage_path('app/' . str_replace(storage_path('app/'), '', $extractPath)));
            $zip->close();

            // Find and process CSV files
            $csvFiles = glob($extractPath . '/*.csv');
            if (empty($csvFiles)) {
                throw new \Exception('No CSV files found in ZIP archive');
            }

            $allPreview = [];
            $allErrors = [];

            foreach ($csvFiles as $csvFile) {
                $result = $this->processCSVFile($csvFile);
                $allErrors = array_merge($allErrors, $result['errors']);
                $allPreview = array_merge($allPreview, $result['preview']);
            }

            return [
                'preview' => $allPreview,
                'errors' => $allErrors
            ];
            
        } finally {
            // Cleanup temp files
            if (file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
        }
    }

    /**
     * Process individual CSV file
     */
    private function processCSVFile($csvPath)
    {
        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            return ['errors' => ["Could not open file: " . basename($csvPath)], 'preview' => []];
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return ['errors' => ["Invalid CSV format in: " . basename($csvPath)], 'preview' => []];
        }

        $errors = [];
        $preview = [];
        $lineNumber = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $lineNumber++;
            
            if (count($data) !== count($headers)) {
                $errors[] = "Line {$lineNumber}: Incorrect number of columns";
                continue;
            }

            $record = array_combine($headers, array_map('trim', $data));
            
            // Basic validation
            if (empty($record['registration_number'])) {
                $errors[] = "Line {$lineNumber}: Missing registration number";
                continue;
            }

            // Check for duplicates in database
            if (Candidate::where('registration_number', $record['registration_number'])->exists()) {
                $errors[] = "Line {$lineNumber}: Duplicate registration number: {$record['registration_number']}";
                continue;
            }

            // Check for duplicates in current batch
            $existingInBatch = collect($preview)->firstWhere('registration_number', $record['registration_number']);
            if ($existingInBatch) {
                $errors[] = "Line {$lineNumber}: Duplicate in batch: {$record['registration_number']}";
                continue;
            }

            $preview[] = $record;
        }

        fclose($handle);
        
        return ['errors' => $errors, 'preview' => $preview];
    }

    /**
     * Validate candidate data
     */
    private function validateCandidateData(Request $request, $candidateId = null)
    {
        $rules = [
            'registration_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('candidates')->ignore($candidateId)
            ],
            'surname' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'maiden_name' => 'nullable|string|max:255',
            'entry_mode' => 'nullable|string|max:255',
            'country' => ['required', 'string', Rule::in(self::WEST_AFRICAN_COUNTRIES)],
            'dob' => 'required|date|before:today',
            'gender' => 'nullable|string|in:Male,Female',
            'nationality' => ['required', 'string', Rule::in(self::WEST_AFRICAN_COUNTRIES)],
            'fellowship_type' => 'nullable|string|max:255',
            'faculty_id' => 'nullable|exists:faculty,id',
            'sub_speciality' => 'nullable|string|max:255',
            'nysc_discharge_or_exemption' => 'nullable|string|max:255',
            'accredited_training_program' => 'nullable|string|max:255',
        ];

        return $request->validate($rules);
    }

        /**
     * Create validator for candidate data array
     */
    private function makeCandidateValidator(array $data)
    {
        $rules = [
            'registration_number' => 'required|string|max:255|regex:/^[A-Z0-9\/\-]+$/',
            'surname' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'other_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'maiden_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'entry_mode' => ['nullable', 'string', Rule::in(['Direct Entry', 'UTME', 'Transfer', 'Postgraduate'])],
            'country' => ['required', 'string', Rule::in(self::WEST_AFRICAN_COUNTRIES)],
            'dob' => 'nullable|date|before:today|after:1900-01-01',
            'gender' => ['nullable', 'string', Rule::in(['Male', 'Female'])],
            'nationality' => ['required', 'string', Rule::in(self::WEST_AFRICAN_COUNTRIES)],
            'fellowship_type' => 'nullable|string|max:255',
            'faculty_id' => 'nullable|integer|exists:faculty,id',
            'sub_speciality' => 'nullable|string|max:255',
            'nysc_discharge_or_exemption' => ['nullable', 'string', Rule::in(['Discharge Certificate', 'Exemption Certificate', 'Not Applicable'])],
            'accredited_training_program' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\-\(\)\s]+$/',
            'address' => 'nullable|string|max:500',
            'postal_address' => 'nullable|string|max:500',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Prepare candidate data for database insertion
     */
    private function prepareCandidateData(array $data)
    {
        return [
            'registration_number' => $data['registration_number'] ?? null,
            'surname' => $data['surname'] ?? null,
            'other_name' => $data['other_name'] ?? null,
            'maiden_name' => $data['maiden_name'] ?? null,
            'entry_mode' => $data['entry_mode'] ?? null,
            'country' => $data['country'] ?? null,
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'nationality' => $data['nationality'] ?? null,
            'fellowship_type' => $data['fellowship_type'] ?? null,
            'faculty_id' => !empty($data['faculty_id']) ? (int)$data['faculty_id'] : null,
            'sub_speciality' => $data['sub_speciality'] ?? null,
            'nysc_discharge_or_exemption' => $data['nysc_discharge_or_exemption'] ?? null,
            'accredited_training_program' => $data['accredited_training_program'] ?? null,
        ];
    }

    /**
     * Recursively delete directory
     */
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        
        rmdir($dir);
    }

    /**
     * Get candidate profile
     */
    public function profile(Candidate $candidate)
    {
        $candidate->load('faculty');
        
        $fullName = " {$candidate->surname} {$candidate->other_name}";
        $facultyName = $candidate->faculty->name ?? 'N/A';
        
        return view('candidates.profile', compact(
            'candidate', 
            'fullName', 
            'facultyName'
        ));
    }

    /**
     * Toggle candidate status
     */
    public function toggleStatus(Candidate $candidate)
    {
        try {
            $candidate->update([
                'status' => $candidate->status === 'active' ? 'inactive' : 'active'
            ]);
            
            $status = $candidate->status === 'active' ? 'activated' : 'deactivated';
            
            return back()->with('success', "Candidate {$status} successfully!");
            
        } catch (\Exception $e) {
            Log::error('Failed to toggle candidate status', [
                'candidate_id' => $candidate->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to update candidate status.');
        }
    }
}

