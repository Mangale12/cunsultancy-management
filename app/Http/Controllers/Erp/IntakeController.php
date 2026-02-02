<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Intake;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class IntakeController extends Controller
{

    public function index(){
        $intakes = Intake::latest()->get();
        return view('admin.intake.index', compact('intakes'));
    }

    public function create(){
        return view('admin.intake.form');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:intakes,name',
        ]);

        try {
            DB::beginTransaction();
            
            $intake = Intake::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('intakes.index')
                ->with('success', 'Intake created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create intake. ' . $e->getMessage());
        }
    }

    public function show(Intake $intake){
        return view('admin.intake.show', compact('intake'));
    }

    public function edit(Intake $intake){
        return view('admin.intake.form', compact('intake'));
    }

    public function update(Request $request, Intake $intake){
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:intakes,name,' . $intake->id,
        ]);

        try {
            DB::beginTransaction();
            
            $intake->update($validated);
            
            DB::commit();
            
            return redirect()
                ->route('intakes.index')
                ->with('success', 'Intake updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update intake. ' . $e->getMessage());
        }
    }
    
    // public function index()
    // {
    //     $intakes = Intake::latest()->get();
        
    //     return Inertia::render('intakes/Index', [
    //         'intakes' => $intakes,
    //         'status' => session('status'),
    //         'success' => session('success'),
    //     ]);
    // }

    // public function create()
    // {
    //     return Inertia::render('intakes/Create');
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255|unique:intakes,name',
    //     ]);

    //     try {
    //         DB::beginTransaction();
            
    //         $intake = Intake::create($validated);
            
    //         DB::commit();
            
    //         return redirect()
    //             ->route('intakes.index')
    //             ->with('success', 'Intake created successfully.');
                
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Failed to create intake. ' . $e->getMessage());
    //     }
    // }

    // public function show(Intake $intake)
    // {
    //     return Inertia::render('intakes/Show', [
    //         'intake' => $intake,
    //     ]);
    // }

    // public function edit(Intake $intake)
    // {
    //     return Inertia::render('intakes/Edit', [
    //         'intake' => $intake,
    //     ]);
    // }

    // public function update(Request $request, Intake $intake)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255|unique:intakes,name,' . $intake->id,
    //     ]);

    //     try {
    //         DB::beginTransaction();
            
    //         $intake->update($validated);
            
    //         DB::commit();
            
    //         return redirect()
    //             ->route('intakes.index')
    //             ->with('success', 'Intake updated successfully.');
                
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Failed to update intake. ' . $e->getMessage());
    //     }
    // }

    // public function destroy(Intake $intake)
    // {
    //     try {
    //         DB::beginTransaction();
            
    //         $intake->delete();
            
    //         DB::commit();
            
    //         return redirect()
    //             ->route('intakes.index')
    //             ->with('success', 'Intake deleted successfully.');
                
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Failed to delete intake. ' . $e->getMessage());
    //     }
    // }
}
