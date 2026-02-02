<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Branch;
use App\Models\Agent;
use App\Models\Country;
use App\Models\State;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\University;
use App\Models\Intake;
use App\Models\ApplicationYear;
use App\Models\DocumentType;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    public function index()
    {
        // Using the scope defined in your Model
        $students = Student::with(['branch', 'agent', 'course'])->latest()->get();
        return view('admin.student.index', compact('students'));
    }

    public function create()
    {
        $countries = Country::all();
        $branches = Branch::all();
        $agents = Agent::all();
        $courses = Course::all(); 
        $states = State::all();
        
        return view('admin.student.form', compact('countries', 'branches', 'agents', 'courses', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string',
            'branch_id' => 'required|exists:branches,id',
            'agent_id' => 'nullable|exists:agents,id',
            'course_id' => 'required|exists:courses,id',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'image_path' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('students', 'public');
        }

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student registered successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return back()->with('success', 'Student deleted.');
    }

    public function edit(Student $student)
    {
        $countries = Country::all();
        $branches = Branch::all();
        $agents = Agent::all();
        $courses = Course::all();
        $states = State::all();
        
        return view('admin.student.form', compact('student', 'countries', 'branches', 'agents', 'courses', 'states'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'branch_id' => 'required',
            'course_id' => 'required',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('students', 'public');
        }

        $student->update($validated);
        return redirect()->route('students.index')->with('success', 'Student updated.');
    }

    public function show(Student $student)
    {
        $universities = University::all();
        $courses = Course::all();
        $intakes = Intake::all();
        $applicationYears = ApplicationYear::all();
        $documentTypes = DocumentType::all();
        
        // Load student with documents
        $student->load(['documents.documentType']);
        $documentTypes = DocumentType::all();
        $student->load(['branch', 'agent', 'course', 'country', 'state']);
        return view('admin.student.show', compact('student', 'universities', 'courses', 'intakes', 'applicationYears', 'documentTypes'));
    }

    /**
     * Complete the student application process
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function completeApplication(Request $request, Student $student)
    {
        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:pending,in_review,approved,rejected,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Update application status
        $updateData = [
            'application_status' => $validated['status'],
            'application_notes' => $validated['notes'] ?? null,
        ];

        // Set completion timestamp if status is completed
        if ($validated['status'] === 'completed') {
            $updateData['application_completed_at'] = now();
        }

        $student->update($updateData);

        // Add activity log
        activity()
            ->causedBy(auth()->user())
            ->performedOn($student)
            ->withProperties(['status' => $validated['status']])
            ->log('Application status updated to ' . $validated['status']);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Application status updated successfully!');
    }

    /**
     * Show the application form for a student
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\View\View
     */
    public function showApplicationForm(Student $student)
    {
        $student->load(['course', 'university', 'documents']);
        $universities = University::all();
        
        return view('admin.student.application', compact('student', 'universities'));
    }

    /**
     * Handle document upload for a student
     */
    public function uploadDocument(Request $request, Student $student)
    {
        $request->validate([
            'documents' => 'required_without:bulk_documents|array',
            'documents.*.title' => 'required_with:documents|string|max:255',
            'documents.*.document_type_id' => 'required_with:documents|exists:document_types,id',
            'documents.*.file' => 'required_with:documents|file|max:10240', 
            'documents.*.expiry_date' => 'nullable|date',
            'documents.*.notes' => 'nullable|string',
            'bulk_documents' => 'nullable|array',
            'bulk_documents.*' => 'file|max:10240',
        ]);
    
        DB::beginTransaction();
    
        try {
            // 1. Handle Individual Document Uploads
            if ($request->has('documents')) {
                foreach ($request->input('documents') as $index => $docData) {
                    if ($request->hasFile("documents.$index.file")) {
                        $file = $request->file("documents.$index.file");
                        $path = $file->store('documents/students/' . $student->id, 'public');
                        
                        $student->documents()->create([
                            'title' => $docData['title'],
                            'document_type_id' => $docData['document_type_id'],
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getClientMimeType(),
                            'expiry_date' => $docData['expiry_date'] ?? null,
                            'notes' => $docData['notes'] ?? null,
                            'uploaded_by' => auth()->id(),
                        ]);
                    }
                }
            }
    
            // 2. Handle Bulk Document Uploads
            if ($request->hasFile('bulk_documents')) {
                $defaultType = DocumentType::where('status', 'active')->first();
                
                foreach ($request->file('bulk_documents') as $file) {
                    $path = $file->store('documents/students/' . $student->id . '/bulk', 'public');
                    
                    $student->documents()->create([
                        'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        'document_type_id' => $defaultType ? $defaultType->id : null,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                        'uploaded_by' => auth()->id(),
                        'notes' => 'Bulk uploaded file',
                    ]);
                }
            }
    
            DB::commit();
    
            $message = 'Documents uploaded successfully!';
            return $request->wantsJson() 
                ? response()->json(['success' => true, 'message' => $message, 'redirect' => route('students.show', $student->id)])
                : redirect()->route('students.show', $student->id)->with('success', $message);
    
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded files if DB failed
            Storage::disk('public')->deleteDirectory('documents/students/' . $student->id . '/temp');
    
            Log::error('Upload Error: ' . $e->getMessage());
    
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], 500)
                : back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
