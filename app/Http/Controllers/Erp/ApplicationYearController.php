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

    public function store(Request $request)
    {
        $request->validate(['year' => 'required|string|unique:application_years,year']);
        $year = ApplicationYear::create(['year' => $request->year]);
        return redirect()->route('application-years.index');
    }

    public function update(Request $request, ApplicationYear $applicationYear)
    {
        $request->validate(['year' => 'required|string|unique:application_years,year,' . $applicationYear->id]);
        $applicationYear->update(['year' => $request->year]);
        return redirect()->route('application-years.index');
    }

    public function destroy(ApplicationYear $applicationYear)
    {
        $applicationYear->delete();
        return redirect()->route('application-years.index');
    }
}
