<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\DocumentType;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $category = $request->get('category', 'all');
        $student_id = $request->get('student_id');
        $perPage = $request->get('per_page', 10);

        $query = Document::with(['student', 'documentType', 'verifiedBy'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('file_name', 'like', "%{$search}%")
                      ->orWhereHas('student', function ($studentQuery) use ($search) {
                          $studentQuery->where('first_name', 'like', "%{$search}%")
                                     ->orWhere('last_name', 'like', "%{$search}%")
                                     ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->when($status !== 'all', function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($category !== 'all', function ($query, $category) {
                $query->whereHas('documentType', function ($typeQuery) use ($category) {
                    $typeQuery->where('category', $category);
                });
            })
            ->when($student_id, function ($query, $student_id) {
                $query->where('student_id', $student_id);
            })
            ->orderBy('created_at', 'desc');

        $documents = $query->paginate($perPage);
        $documentTypes = DocumentType::active()->orderBy('sort_order')->get()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'category' => $type->category,
                'allowed_file_types' => is_array($type->allowed_file_types) ? $type->allowed_file_types : json_decode($type->allowed_file_types ?? '[]', true),
                'max_file_size' => $type->max_file_size,
                'is_required' => $type->is_required,
                'has_expiry' => $type->has_expiry,
                'allows_multiple_files' => $type->allows_multiple_files ?? false,
                'max_files' => $type->max_files ?? 1,
            ];
        });
        $students = Student::orderBy('first_name')->get();

        return Inertia::render('documents/index', [
            'documents' => $documents,
            'documentTypes' => $documentTypes,
            'students' => $students,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'category' => $category,
                'student_id' => $student_id,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create(Request $request): InertiaResponse
    {
        $student_id = $request->get('student_id');
        
        $documentTypes = DocumentType::where('is_active', true)->orderBy('sort_order')->get()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'category' => $type->category,
                'allowed_file_types' => json_decode($type->allowed_file_types, true) || [],
                'max_file_size' => $type->max_file_size,
                'is_required' => $type->is_required,
                'has_expiry' => $type->has_expiry,
                'allows_multiple_files' => $type->allows_multiple_files ?? false,
                'max_files' => $type->max_files ?? 1,
            ];
        });
        $students = Student::orderBy('first_name')->get();

        return Inertia::render('documents/create', [
            'documentTypes' => $documentTypes,
            'students' => $students,
            'default_student_id' => $student_id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Debug: Log what we're receiving
        \Log::info('DocumentController@store - Request data:', [
            'all' => $request->all(),
            'files' => $request->file('files'),
            'hasFiles' => $request->hasFile('files'),
            'filesCount' => $request->hasFile('files') ? count($request->file('files')) : 0,
        ]);

        try {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'document_type_id' => 'required|exists:document_types,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'files' => 'required|array|min:1',
                'files.*' => 'file|max:10240', // 10MB max per file
                'file_descriptions' => 'nullable|array',
                'file_descriptions.*' => 'nullable|string|max:255',
                'primary_file_index' => 'nullable|integer|min:0',
                'expiry_date' => 'nullable|date|after:today',
                'is_required' => 'sometimes|boolean',
                'is_public' => 'sometimes|boolean',
            ]);
            
            $files = $request->file('files');
            \Log::info('Files received:', ['count' => count($files)]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            throw $e;
        }

        $documentType = DocumentType::findOrFail($validated['document_type_id']);
        $files = $request->file('files');
        $fileDescriptions = $validated['file_descriptions'] ?? [];
        $primaryFileIndex = $validated['primary_file_index'] ?? 0;

        // Check if document type allows multiple files
        if (!$documentType->allows_multiple_files && count($files) > 1) {
            return back()->withErrors([
                'files' => 'This document type only allows single file upload.'
            ]);
        }

        // Check file count limit
        if (count($files) > $documentType->max_files) {
            return back()->withErrors([
                'files' => "Maximum {$documentType->max_files} files allowed for this document type."
            ]);
        }

        // Validate each file
        foreach ($files as $index => $file) {
            // Validate file type
            if (!$documentType->isFileTypeAllowed($file->getClientOriginalExtension())) {
                return back()->withErrors([
                    "files.{$index}" => 'File type not allowed. Allowed types: ' . implode(', ', $documentType->allowed_file_types)
                ]);
            }

            // Validate file size
            if ($file->getSize() > $documentType->max_file_size * 1024) {
                return back()->withErrors([
                    "files.{$index}" => 'File size exceeds maximum allowed size of ' . $documentType->max_file_size_mb . 'MB'
                ]);
            }
        }

        // Create document
        $document = Document::create([
            'student_id' => $validated['student_id'],
            'document_type_id' => $validated['document_type_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'expiry_date' => $validated['expiry_date'],
            'is_required' => $validated['is_required'] ?? false,
            'is_public' => $validated['is_public'] ?? false,
        ]);

        // Store files
        foreach ($files as $index => $file) {
            // Generate unique file path
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            // Calculate file hash
            $fileHash = md5_file($file->getPathname());

            // Check for duplicate file hash
            $existingFile = DocumentFile::where('file_hash', $fileHash)->first();
            if ($existingFile) {
                return back()->withErrors([
                    "files.{$index}" => 'This file has already been uploaded. Duplicate files are not allowed.'
                ]);
            }

            // Create document file record
            DocumentFile::create([
                'document_id' => $document->id,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'file_hash' => $fileHash,
                'description' => $fileDescriptions[$index] ?? null,
                'is_primary' => $index === $primaryFileIndex,
                'sort_order' => $index,
            ]);
        }

        return redirect('/documents')
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Document $document): InertiaResponse
    {
        $document->load(['student', 'documentType', 'verifiedBy', 'verifications' => function ($query) {
            $query->with('verifiedBy')->latest();
        }, 'files' => function ($query) {
            $query->ordered();
        }]);

        return Inertia::render('documents/show', [
            'document' => $document,
        ]);
    }

    public function edit(Document $document): InertiaResponse
    {
        $document->load(['student', 'documentType']);
        
        $documentTypes = DocumentType::active()->orderBy('sort_order')->get()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'category' => $type->category,
                'allowed_file_types' => is_array($type->allowed_file_types) ? $type->allowed_file_types : json_decode($type->allowed_file_types ?? '[]', true),
                'max_file_size' => $type->max_file_size,
                'is_required' => $type->is_required,
                'has_expiry' => $type->has_expiry,
                'allows_multiple_files' => $type->allows_multiple_files ?? false,
                'max_files' => $type->max_files ?? 1,
            ];
        });
        $students = Student::orderBy('first_name')->get();

        return Inertia::render('documents/edit', [
            'document' => $document,
            'documentTypes' => $documentTypes,
            'students' => $students,
        ]);
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'document_type_id' => 'required|exists:document_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expiry_date' => 'nullable|date|after:today',
            'is_required' => 'boolean',
            'is_public' => 'boolean',
        ]);

        $document->update($validated);

        return redirect('/documents')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect('/documents')
            ->with('success', 'Document deleted successfully.');
    }

    public function download(Document $document): Response
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function verify(Request $request, Document $document): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,needs_revision',
            'notes' => 'nullable|string',
            'rejection_reason' => 'required_if:status,rejected|string',
            'verification_checklist' => 'nullable|array',
        ]);

        // Update document status
        $document->update([
            'status' => $validated['status'] === 'approved' ? 'verified' : $validated['status'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Create verification record
        $document->verifications()->create([
            'verified_by' => auth()->id(),
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'verification_checklist' => $validated['verification_checklist'] ?? null,
            'verified_at' => now(),
        ]);

        return redirect('/documents/' . $document->id)
            ->with('success', 'Document verification status updated.');
    }

    public function studentDocuments(Student $student): InertiaResponse
    {
        $documents = $student->documents()
            ->with(['documentType', 'verifiedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        $documentTypes = DocumentType::active()->orderBy('sort_order')->get()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'category' => $type->category,
                'allowed_file_types' => is_array($type->allowed_file_types) ? $type->allowed_file_types : json_decode($type->allowed_file_types ?? '[]', true),
                'max_file_size' => $type->max_file_size,
                'is_required' => $type->is_required,
                'has_expiry' => $type->has_expiry,
                'allows_multiple_files' => $type->allows_multiple_files ?? false,
                'max_files' => $type->max_files ?? 1,
            ];
        });

        return Inertia::render('documents/student-documents', [
            'student' => $student,
            'documents' => $documents,
            'documentTypes' => $documentTypes,
        ]);
    }
}
