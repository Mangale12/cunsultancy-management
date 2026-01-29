<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\StudentApplication;
use App\Models\Student;
use App\Models\University;
use App\Models\Course;
use App\Models\StudentPayment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentApplicationController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $visaStatus = $request->get('visa_status');
        $university = $request->get('university');
        $perPage = $request->get('per_page', 10);

        $query = StudentApplication::with(['student', 'university', 'course', 'payments'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('student', function ($student) use ($search) {
                        $student->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                    });
                });
            })
            ->when($status, function ($query, $status) {
                $query->byStatus($status);
            })
            ->when($visaStatus, function ($query, $visaStatus) {
                $query->byVisaStatus($visaStatus);
            })
            ->when($university, function ($query, $university) {
                $query->where('university_id', $university);
            })
            ->orderBy('created_at', 'desc');

        $applications = $query->paginate($perPage);
        $universities = University::orderBy('name')->get();

        return Inertia::render('student-applications/index', [
            'applications' => $applications,
            'universities' => $universities,
            'filters' => $request->only(['search', 'status', 'visa_status', 'university', 'per_page']),
        ]);
    }

    public function create(Request $request): Response
    {
        $student_id = $request->get('student_id');
        
        $students = Student::orderBy('first_name')->get();
        $universities = University::orderBy('name')->get();

        return Inertia::render('student-applications/create', [
            'students' => $students,
            'universities' => $universities,
            'default_student_id' => $student_id,
        ]);
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

        return redirect()->route('student-applications.show', $application->id)
            ->with('success', 'Student application created successfully.');
    }

    public function show(StudentApplication $studentApplication): Response
    {
        $studentApplication->load([
            'student',
            'university',
            'course',
            'payments' => function ($query) {
                $query->orderBy('due_date');
            }
        ]);

        return Inertia::render('student-applications/show', [
            'application' => $studentApplication,
        ]);
    }

    public function edit(StudentApplication $studentApplication): Response
    {
        $studentApplication->load(['student', 'university', 'course']);
        
        $students = Student::orderBy('first_name')->get();
        $universities = University::orderBy('name')->get();
        $courses = Course::where('university_id', $studentApplication->university_id)->get();

        return Inertia::render('student-applications/edit', [
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

        return redirect()->route('student-applications.show', $studentApplication->id)
            ->with('success', 'Student application updated successfully.');
    }

    public function destroy(StudentApplication $studentApplication)
    {
        $studentApplication->delete();

        return redirect()->route('student-applications.index')
            ->with('success', 'Student application deleted successfully.');
    }

    public function updateStatus(Request $request, StudentApplication $studentApplication)
    {
        $validated = $request->validate([
            'application_status' => 'required|in:draft,submitted,under_review,admitted,rejected,enrolled,withdrawn,deferred',
            'visa_status' => 'required|in:not_started,documents_collected,application_submitted,interview_scheduled,interview_completed,approved,rejected,issued',
            'pre_departure_status' => 'required|in:not_started,documents_ready,flight_booked,accommodation_arranged,insurance_done,ready',
        ]);

        $studentApplication->update($validated);

        return back()->with('success', 'Application status updated successfully.');
    }
}
