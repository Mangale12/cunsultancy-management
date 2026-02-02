<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{

    public function index(){
        $courses = Course::all();
        return view('admin.course.index', compact('courses'));
    }


    public function create()
    {
        $universities = University::all();
        return view('admin.course.form', compact('universities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id'   => 'required|exists:universities,id',
            'name'            => 'required|string|max:255',
            'level'           => 'required|string', // e.g., Bachelor, Master
            'duration_months' => 'required|integer|min:1',
            'tuition_fee'     => 'required|numeric|min:0',
            'currency'        => 'required|string|max:10',
            'image_path'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('programs', 'public');
        }

        Course::create($validated);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $universities = University::all();
        return view('admin.course.form', compact('course', 'universities'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'university_id'   => 'required|exists:universities,id',
            'name'            => 'required|string|max:255',
            'level'           => 'required|string',
            'duration_months' => 'required|integer|min:1',
            'tuition_fee'     => 'required|numeric|min:0',
            'currency'        => 'required|string|max:10',
            'image_path'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image_path')) {
            if ($course->image_path) { Storage::disk('public')->delete($course->image_path); }
            $validated['image_path'] = $request->file('image_path')->store('programs', 'public');
        }

        $course->update($validated);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function show(Course $course)
    {
        // Load course with related data
        $course->load(['university']);
        
        return view('admin.course.show', compact('course'));
    }

    public function destroy(Course $course)
    {
        try {
            // Check if course has students
            if ($course->students()->exists()) {
                return back()
                    ->with('error', 'Cannot delete course. It has associated students.');
            }

            // Delete course image if exists
            if ($course->image_path) {
                Storage::disk('public')->delete($course->image_path);
            }

            $course->delete();

            return redirect()
                ->route('courses.index')
                ->with('success', 'Course deleted successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete course. Please try again.');
        }
    }
    // public function index(Request $request): Response
    // {
    //     $search = $request->get('search');
    //     $university = $request->get('university');
    //     $level = $request->get('level');
    //     $perPage = $request->get('per_page', 10);

    //     $query = Course::with('university')
    //         ->when($search, function ($query, $search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                   ->orWhere('level', 'like', "%{$search}%");
    //             });
    //         })
    //         ->when($university, function ($query, $university) {
    //             $query->where('university_id', $university);
    //         })
    //         ->when($level, function ($query, $level) {
    //             $query->where('level', $level);
    //         })
    //         ->orderBy('created_at', 'desc');

    //     $courses = $query->paginate($perPage);
    //     $universities = University::all();

    //     return Inertia::render('courses/index', [
    //         'courses' => $courses,
    //         'universities' => $universities,
    //         'filters' => [
    //             'search' => $search,
    //             'university' => $university,
    //             'level' => $level,
    //             'per_page' => $perPage,
    //         ],
    //     ]);
    // }

    // public function create(): Response
    // {
    //     $universities = University::all();

    //     return Inertia::render('courses/create', [
    //         'universities' => $universities,
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'level' => 'required|string|max:100',
    //         'duration_months' => 'required|integer|min:1',
    //         'tuition_fee' => 'required|numeric|min:0',
    //         'currency' => 'required|string|max:3',
    //         'university_id' => 'required|exists:universities,id',
    //         'image_path' => 'nullable|string|max:255',
    //     ]);

    //     Course::create($validated);

    //     return redirect()->route('courses.index')
    //         ->with('success', 'Course created successfully.');
    // }

    // public function show(Course $course): Response
    // {
    //     $course->load(['university', 'students']);

    //     return Inertia::render('courses/show', [
    //         'course' => $course,
    //     ]);
    // }

    // public function edit(Course $course): Response
    // {
    //     $universities = University::all();

    //     return Inertia::render('courses/edit', [
    //         'course' => $course,
    //         'universities' => $universities,
    //     ]);
    // }

    // public function update(Request $request, Course $course)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'level' => 'required|string|max:100',
    //         'duration_months' => 'required|integer|min:1',
    //         'tuition_fee' => 'required|numeric|min:0',
    //         'currency' => 'required|string|max:3',
    //         'university_id' => 'required|exists:universities,id',
    //         'image_path' => 'nullable|string|max:255',
    //     ]);

    //     $course->update($validated);

    //     return redirect()->route('courses.index')
    //         ->with('success', 'Course updated successfully.');
    // }

    // public function destroy(Course $course)
    // {
    //     $course->delete();

    //     return redirect()->route('courses.index')
    //         ->with('success', 'Course deleted successfully.');
    // }

    // /**
    //  * Get courses by university for API
    //  */
    // public function getCoursesByUniversity($universityId)
    // {
    //     $courses = Course::where('university_id', $universityId)
    //         ->orderBy('name')
    //         ->get();

    //     return response()->json([
    //         'courses' => $courses
    //     ]);
    // }
}
