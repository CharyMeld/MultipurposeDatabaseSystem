<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document; // your Document model
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
  


    public function upload(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|integer',
            'application_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'location' => 'required|file|mimes:pdf,jpeg,png,jpg|max:204800', // max 200MB in KB
        ]);

        $file = $request->file('location');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/uploads/documents', $filename);

        $document = new Document();
        $document->candidate_id = $request->candidate_id;
        $document->application_id = $request->application_id;
        $document->title = $request->title;
        $document->description = $request->description;
        $document->status = $request->status ?? 1;
        $document->location = 'uploads/documents/' . $filename;
        $document->time_created = now();
        $document->save();

        return response()->json(['message' => 'Document uploaded successfully', 'document' => $document], 201);
    }
    
    /**
     * Return applications and documents for the candidate.
     */
    public function getApplicationsAndDocuments($candidateId)
    {
        // Get all applications for candidate
        $applications = Application::where('candidate_id', $candidateId)
            ->select('id', 'type', 'name', 'title') // Select necessary fields
            ->get();

        // Get all documents linked to candidate's applications
        $applicationIds = $applications->pluck('id');

        $documents = Document::whereIn('application_id', $applicationIds)
            ->select('id', 'application_id', 'title', 'description', 'location', 'time_created')
            ->orderBy('time_created', 'desc')
            ->get();

        return response()->json([
            'applications' => $applications,
            'documents' => $documents,
        ]);
    }

    /**
     * Delete a document and its file.
     */
    public function destroy(Document $document)
    {
        // Delete file from storage (assuming storage path 'storage/app/public/uploads/documents')
        $filePath = storage_path('app/public/' . $document->location);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $document->delete();

        return response()->json(['message' => 'Document deleted successfully']);
    }
}

