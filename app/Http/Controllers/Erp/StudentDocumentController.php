<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\StudentDocument;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentDocumentController extends Controller
{
    /**
     * Display a listing of documents for a student.
     */
    public function index(Student $student)
    {
        $documents = $student->documents()
            ->latest()
            ->paginate(10);
            
        return view('admin.student.documents.index', compact('student', 'documents'));
    }

    /**
     * Show the form for uploading a new document.
     */
    public function create(Student $student)
    {
        $documentTypes = [
            'passport' => 'Passport',
            'transcript' => 'Academic Transcript',
            'ielts' => 'IELTS/English Test',
            'recommendation' => 'Letter of Recommendation',
            'sop' => 'Statement of Purpose',
            'financial' => 'Financial Documents',
            'other' => 'Other Document'
        ];
        
        return view('admin.student.documents.create', compact('student', 'documentTypes'));
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'document' => ['required', 'file', 'max:10240'], // 10MB max
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        try {
            $file = $request->file('document');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            $filePath = $file->storeAs('student-documents', $fileName, 'public');

            $document = new StudentDocument([
                'document_type' => $validated['document_type'],
                'title' => $validated['title'],
                'file_path' => $filePath,
                'file_name' => $originalName,
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'notes' => $validated['notes'] ?? null,
                'is_verified' => false,
            ]);

            $student->documents()->save($document);

            return redirect()
                ->route('students.documents.index', $student->id)
                ->with('success', 'Document uploaded successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Document upload failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to upload document. Please try again.');
        }
    }

    /**
     * Display the specified document.
     */
    public function show(Student $student, StudentDocument $document)
    {
        $this->authorize('view', $document);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }
        
        return response()->file(storage_path('app/public/' . $document->file_path));
    }

    /**
     * Download the specified document.
     */
    public function download(Student $student, StudentDocument $document)
    {
        $this->authorize('view', $document);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }
        
        return Storage::disk('public')->download(
            $document->file_path, 
            $document->file_name
        );
    }

    /**
     * Show the form for editing document metadata.
     */
    public function edit(Student $student, StudentDocument $document)
    {
        $this->authorize('update', $document);
        
        $documentTypes = [
            'passport' => 'Passport',
            'transcript' => 'Academic Transcript',
            'ielts' => 'IELTS/English Test',
            'recommendation' => 'Letter of Recommendation',
            'sop' => 'Statement of Purpose',
            'financial' => 'Financial Documents',
            'other' => 'Other Document'
        ];
        
        return view('admin.student.documents.edit', compact('student', 'document', 'documentTypes'));
    }

    /**
     * Update document metadata.
     */
    public function update(Request $request, Student $student, StudentDocument $document)
    {
        $this->authorize('update', $document);
        
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_verified' => ['sometimes', 'boolean'],
        ]);

        try {
            $document->update($validated);
            
            if ($request->has('is_verified') && $validated['is_verified'] && !$document->verified_at) {
                $document->update([
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);
            }

            return redirect()
                ->route('students.documents.index', $student->id)
                ->with('success', 'Document updated successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Document update failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to update document. Please try again.');
        }
    }

    /**
     * Remove the specified document.
     */
    public function destroy(Student $student, StudentDocument $document)
    {
        $this->authorize('delete', $document);
        
        try {
            // Delete the file from storage
            Storage::disk('public')->delete($document->file_path);
            
            // Delete the database record
            $document->delete();
            
            return redirect()
                ->route('students.documents.index', $student->id)
                ->with('success', 'Document deleted successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Document deletion failed: ' . $e->getMessage());
            return back()
                ->with('error', 'Failed to delete document. Please try again.');
        }
    }

}
