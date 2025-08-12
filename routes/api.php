<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/candidates/{candidate}/applications-documents', [CandidateDocumentController::class, 'getApplicationsAndDocuments']);
Route::delete('/documents/{document}', [CandidateDocumentController::class, 'destroy']);

