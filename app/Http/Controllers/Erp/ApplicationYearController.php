<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\ApplicationYear;
use Illuminate\Http\Request;

class ApplicationYearController extends Controller
{
    public function index()
    {
        $applicationYears = ApplicationYear::all();
        return view('admin.application_year.index', compact('applicationYears'));
    }

    public function create()
    {
        return view('admin.application_year.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string|unique:application_years,year',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        ApplicationYear::create($validated);

        return redirect()
            ->route('application-years.index')
            ->with('success', 'Application year created successfully.');
    }

    public function show(ApplicationYear $applicationYear)
    {
        return view('admin.application_year.show', compact('applicationYear'));
    }

    public function edit(ApplicationYear $applicationYear)
    {
        return view('admin.application_year.form', compact('applicationYear'));
    }

    public function update(Request $request, ApplicationYear $applicationYear)
    {
        $validated = $request->validate([
            'year' => 'required|string|unique:application_years,year,' . $applicationYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $applicationYear->update($validated);

        return redirect()
            ->route('application-years.index')
            ->with('success', 'Application year updated successfully.');
    }

    public function destroy(ApplicationYear $applicationYear)
    {
        try {
            // Check if application year has related records
            if ($applicationYear->students()->exists()) {
                return back()
                    ->with('error', 'Cannot delete application year. It has associated students.');
            }

            $applicationYear->delete();

            return redirect()
                ->route('application-years.index')
                ->with('success', 'Application year deleted successfully.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete application year. Please try again.');
        }
    }
}
