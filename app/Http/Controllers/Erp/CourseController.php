<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\University;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->get('search');
        $university = $request->get('university');
        $level = $request->get('level');
        $perPage = $request->get('per_page', 10);

        $query = Course::with('university')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('level', 'like', "%{$search}%");
                });
            })
            ->when($university, function ($query, $university) {
                $query->where('university_id', $university);
            })
            ->when($level, function ($query, $level) {
                $query->where('level', $level);
            })
            ->orderBy('created_at', 'desc');

        $courses = $query->paginate($perPage);
        $universities = University::all();

        return Inertia::render('courses/index', [
            'courses' => $courses,
            'universities' => $universities,
            'filters' => [
                'search' => $search,
                'university' => $university,
                'level' => $level,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create(): Response
    {
        $universities = University::all();

        return Inertia::render('courses/create', [
            'universities' => $universities,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'duration_months' => 'required|integer|min:1',
            'tuition_fee' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'university_id' => 'required|exists:universities,id',
            'image_path' => 'nullable|string|max:255',
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course): Response
    {
        $course->load(['university', 'students']);

        return Inertia::render('courses/show', [
            'course' => $course,
        ]);
    }

    public function edit(Course $course): Response
    {
        $universities = University::all();

        return Inertia::render('courses/edit', [
            'course' => $course,
            'universities' => $universities,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'duration_months' => 'required|integer|min:1',
            'tuition_fee' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'university_id' => 'required|exists:universities,id',
            'image_path' => 'nullable|string|max:255',
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Get courses by university for API
     */
    public function getCoursesByUniversity($universityId)
    {
        $courses = Course::where('university_id', $universityId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'courses' => $courses
        ]);
    }
}
