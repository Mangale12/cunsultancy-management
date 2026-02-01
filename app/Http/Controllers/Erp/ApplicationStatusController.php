<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\ApplicationStatus;
use Illuminate\Http\Request;

class ApplicationStatusController extends Controller
{
    public function index()
    {
        $applicationStatus = ApplicationStatus::all();
        return view('admin.application_status.index', compact('applicationStatus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:application_statuses,code',
        ]);
        $status = ApplicationStatus::create($request->only(['name', 'code']));
        return redirect()->route('application-status.index')->with('success', 'Application status created successfully.');
    }

    public function update(Request $request, ApplicationStatus $applicationStatus)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:application_statuses,code,' . $applicationStatus->id,
        ]);
        $applicationStatus->update($request->only(['name', 'code']));
        return redirect()->route('application-status.index')->with('success', 'Application status updated successfully.');
    }

    public function destroy(ApplicationStatus $applicationStatus)
    {
        $applicationStatus->delete();
        return redirect()->route('application-status.index')->with('success', 'Application status deleted successfully.');
    }
}
