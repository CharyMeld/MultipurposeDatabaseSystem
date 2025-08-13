<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CandidateProfileController; // <--- Make sure this line is present and correct

Route::get('/candidates/{candidate}/applications-documents', [CandidateDocumentController::class, 'getApplicationsAndDocuments']);
Route::delete('/documents/{document}', [CandidateDocumentController::class, 'destroy']);

// Add this new route:
//Route::get('/candidates/{candidate}/json', [CandidateProfileController::class, 'showJson']); // <--- Add or correct this line


