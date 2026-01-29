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

class StudentController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->get('search');
        $branch = $request->get('branch');
        $agent = $request->get('agent');
        $course = $request->get('course');
        $status = $request->get('status');
        $perPage = $request->get('per_page', 10);

        $query = Student::with(['branch', 'agent', 'course', 'country', 'state'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($branch, function ($query, $branch) {
                $query->where('branch_id', $branch);
            })
            ->when($agent, function ($query, $agent) {
                $query->where('agent_id', $agent);
            })
            ->when($course, function ($query, $course) {
                $query->where('course_id', $course);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc');

        $students = $query->paginate($perPage);
        $branches = Branch::all();
        $agents = Agent::all();
        $courses = Course::all();

        return Inertia::render('erp/students/index', [
            'students' => $students,
            'branches' => $branches,
            'agents' => $agents,
            'courses' => $courses,
            'filters' => [
                'search' => $search,
                'branch' => $branch,
                'agent' => $agent,
                'course' => $course,
                'status' => $status,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create(): Response
    {
        $branches = Branch::all();
        $agents = Agent::all();
        $countries = Country::all();
        $states = State::all();
        $courses = Course::all();

        return Inertia::render('erp/students/create', [
            'branches' => $branches,
            'agents' => $agents,
            'countries' => $countries,
            'states' => $states,
            'courses' => $courses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'branch_id' => 'required|exists:branches,id',
            'agent_id' => 'nullable|exists:agents,id',
            'course_id' => 'nullable|exists:courses,id',
            'status' => 'required|string|in:active,inactive,graduated,suspended',
            'image_path' => 'nullable|string|max:255',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student): Response
    {
        $student->load([
            'branch', 
            'agent', 
            'course', 
            'country', 
            'state',
            'applications.university',
            'applications.course',
            'documents.documentType',
            'payments'
        ]);

        return Inertia::render('erp/students/show', [
            'student' => $student,
        ]);
    }

    public function edit(Student $student): Response
    {
        $branches = Branch::all();
        $agents = Agent::all();
        $countries = Country::all();
        $states = State::all();
        $courses = Course::all();

        return Inertia::render('erp/students/edit', [
            'student' => $student,
            'branches' => $branches,
            'agents' => $agents,
            'countries' => $countries,
            'states' => $states,
            'courses' => $courses,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'branch_id' => 'required|exists:branches,id',
            'agent_id' => 'nullable|exists:agents,id',
            'course_id' => 'nullable|exists:courses,id',
            'status' => 'required|string|in:active,inactive,graduated,suspended',
            'image_path' => 'nullable|string|max:255',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
