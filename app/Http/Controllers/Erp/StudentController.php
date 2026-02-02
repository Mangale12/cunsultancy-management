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

    public function uploadDocument(Request $request, Student $student)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'document_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        StudentDocument::create([
            'student_id' => $student->id,
            'title' => $validated['title'],
            'file_name' => $validated['title'],
            'document_type_id' => $validated['document_type_id'],
            'file_path' => $request->file('document_file')->store('student_documents', 'public'),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Document uploaded successfully!');
    }
}
