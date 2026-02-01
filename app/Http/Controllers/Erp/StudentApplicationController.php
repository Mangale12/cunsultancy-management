<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\StudentApplication;
use App\Models\Student;
use App\Models\University;
use App\Models\Course;
use App\Models\StudentPayment;
use App\Models\Intake;
use App\Models\ApplicationYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentApplicationController extends Controller
{
    public function create(Request $request, ?Student $student = null)
    {
        \Log::info('StudentApplicationController@create called', [
            'student_id' => $student ? $student->id : null,
            'request_data' => $request->all()
        ]);

        $universities = University::orderBy('name')->get(['id', 'name']);
        $courses = Course::orderBy('name')->get(['id', 'name']);
        $intakes = Intake::orderBy('name')->get(['id', 'name']);
        $applicationYears = ApplicationYear::orderBy('year')->get(['id', 'year as name']);
        $students = Student::orderBy('first_name')->get(['id', 'first_name', 'last_name']);

        $data = [
            'universities' => $universities,
            'courses' => $courses,
            'intakes' => $intakes,
            'applicationYears' => $applicationYears,
            'students' => $students,
        ];

        if ($student) {
            $data['student'] = [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
            ];
            $data['default_student_id'] = $student->id;
        } else if ($request->has('student_id')) {
            $data['default_student_id'] = $request->get('student_id');
        }

        \Log::info('Rendering student application form with data', [
            'universities_count' => $universities->count(),
            'courses_count' => $courses->count(),
            'intakes_count' => $intakes->count(),
            'student' => $data['student'] ?? null
        ]);

        return Inertia::render('student-applications/Create', $data);
    }

    public function index(Request $request)
    {
        $query = StudentApplication::all();
        
        // --- Statistics ---
        $stats = [
            'total_applications' => $query->count(),
            'total_tuition'      => $query->sum('tuition_fee'),
            'total_scholarship'  => $query->sum('scholarship_amount'),
            'visa_approved'      => $query->where('visa_status', 'Approved')->count(),
        ];

        $applications = $query;
        $universities = University::orderBy('name')->get();

        return view('admin.application.index', compact('applications', 'universities', 'stats'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'university_id' => 'required|exists:universities,id',
            'course_id' => 'required|exists:courses,id',
            'tuition_fee' => 'nullable|numeric|min:0',
            'scholarship_amount' => 'nullable|numeric|min:0',
            'submission_deadline' => 'nullable|date',
            'admission_deadline' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['application_status'] = 'draft';
        $validated['visa_status'] = 'not_started';
        $validated['pre_departure_status'] = 'not_started';

        $application = StudentApplication::create($validated);

        // Create initial application fee payment
        StudentPayment::create([
            'student_application_id' => $application->id,
            'student_id' => $application->student_id,
            'payment_type' => 'application_fee',
            'payment_method' => 'bank_transfer',
            'amount' => 100, // Default application fee
            'currency' => 'USD',
            'due_date' => now()->addDays(7),
            'status' => 'pending',
        ]);

        return redirect()->route('students.show', $application->student_id)
            ->with('success', 'Student application created successfully.');
    }
    public function show($id)
    {
        $application = StudentApplication::with([
            'student.documents', // Get student docs through the application
            'university.country',
            'course',
            'logs.user',        // Needed for the status timeline
            'payments' => fn($q) => $q->orderBy('due_date')
        ])->findOrFail($id);
    
        // Fetch dropdown data for the "Apply More" modal
        $universities = \App\Models\University::all();
        $intakes = \App\Models\Intake::all();
        $courses = \App\Models\Course::all();
    
        return view('admin.application.show', compact('application', 'universities', 'intakes', 'courses'));
    }

    public function edit(StudentApplication $studentApplication): Response
    {
        $studentApplication->load(['student', 'university', 'course']);
        
        $students = Student::orderBy('first_name')->get();
        $universities = University::orderBy('name')->get();
        $courses = Course::where('university_id', $studentApplication->university_id)->get();

        return view('admin.applications.edit', [
            'application' => $studentApplication,
            'students' => $students,
            'universities' => $universities,
            'courses' => $courses,
        ]);
    }

    public function update(Request $request, StudentApplication $studentApplication)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'university_id' => 'required|exists:universities,id',
            'course_id' => 'required|exists:courses,id',
            'application_status' => 'required|in:draft,submitted,under_review,admitted,rejected,enrolled,withdrawn,deferred',
            'visa_status' => 'required|in:not_started,documents_collected,application_submitted,interview_scheduled,interview_completed,approved,rejected,issued',
            'pre_departure_status' => 'required|in:not_started,documents_ready,flight_booked,accommodation_arranged,insurance_done,ready',
            'application_date' => 'nullable|date',
            'submission_deadline' => 'nullable|date',
            'admission_deadline' => 'nullable|date',
            'visa_application_date' => 'nullable|date',
            'visa_interview_date' => 'nullable|date',
            'visa_approval_date' => 'nullable|date',
            'tuition_fee' => 'nullable|numeric|min:0',
            'scholarship_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $studentApplication->update($validated);

        return redirect()->route('admin.student-applications.show', $studentApplication->id)
            ->with('success', 'Student application updated successfully.');
    }

    public function destroy(StudentApplication $studentApplication)
    {
        $studentApplication->delete();

        return redirect()->route('student-applications.index')
            ->with('success', 'Student application deleted successfully.');
    }

    // public function updateStatus(Request $request, StudentApplication $studentApplication)
    // {
    //     $validated = $request->validate([
    //         'application_status' => 'required|in:draft,submitted,under_review,admitted,rejected,enrolled,withdrawn,deferred',
    //         'visa_status' => 'required|in:not_started,documents_collected,application_submitted,interview_scheduled,interview_completed,approved,rejected,issued',
    //         'pre_departure_status' => 'required|in:not_started,documents_ready,flight_booked,accommodation_arranged,insurance_done,ready',
    //     ]);

    //     $studentApplication->update($validated);

    //     return back()->with('success', 'Application status updated successfully.');
    // }


    public function updateStatus(Request $request, $id)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            'status' => 'required|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        $application = StudentApplication::findOrFail($id);

        try {
            // 2. Wrap in a transaction for data integrity
            DB::transaction(function () use ($application, $validated) {
                
                // Update the current status on the application
                $application->update([
                    'status' => $validated['status']
                ]);

                // 3. Create the log entry
                // Assumes relationship: $application->logs()
                $application->logs()->create([
                    'user_id' => Auth::id(), // Person making the change
                    'status'  => $validated['status'],
                    'comment' => $validated['comment'] ?? 'Status updated manually.',
                ]);
            });

            return back()->with('success', 'Application status updated and logged successfully.');

        } catch (\Exception $e) {
            dd($e);
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
