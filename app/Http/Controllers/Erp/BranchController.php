<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Erp\BranchStoreRequest;
use App\Http\Requests\Erp\BranchUpdateRequest;
use App\Models\Branch;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class BranchController extends Controller
{

    public function index()
    {
        $branches = Branch::all();
        return view('admin.branch.index', compact('branches'));
    }

    public function create(){
        $countries = Country::all();
        $states = State::all();
        return view('admin.branch.form', compact('countries', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'code' => 'required|max:50|unique:branches',
            'country_id' => 'required',
            'state_id' => 'required',
            'email' => 'nullable|email',
            'is_active' => 'boolean'
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        // Load branch with related data
        $branch->load(['country', 'state', 'employees', 'students', 'agents']);
        
        return view('admin.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $countries = Country::all();
        $states = State::where('country_id', $branch->country_id)->get();
        return view('admin.branch.form', compact('branch', 'countries', 'states'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'code' => 'required|max:50|unique:branches,code,' . $branch->id,
            'country_id' => 'required',
            'state_id' => 'required',
            'email' => 'nullable|email',
            'is_active' => 'boolean'
        ]);

        $branch->update($validated);
        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    // AJAX method for dynamic dropdown
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->get(['id', 'name']);
        return response()->json($states);
    }
    
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request): Response
    // {
    //     $this->authorize('viewAny', Branch::class);

    //     $search = $request->get('search');
    //     $perPage = $request->get('per_page', 10);

    //     $branches = Branch::visibleTo($request->user())
    //         ->when($search, function ($query, $search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                   ->orWhere('code', 'like', "%{$search}%")
    //                   ->orWhere('address', 'like', "%{$search}%")
    //                   ->orWhere('manager_name', 'like', "%{$search}%");
    //             });
    //         })
    //         ->orderBy('name')
    //         ->paginate($perPage)
    //         ->withQueryString();

    //     return Inertia::render('erp/branches/index', [
    //         'branches' => $branches,
    //         'filters' => [
    //             'search' => $search,
    //             'per_page' => $perPage,
    //         ],
    //     ]);
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create(): Response
    // {
    //     $this->authorize('create', Branch::class);

    //     return Inertia::render('erp/branches/create', [
    //         'countries' => Country::orderBy('name')->get(),
    //         'states' => State::orderBy('name')->get(),
    //     ]);
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(BranchStoreRequest $request): RedirectResponse
    // {
    //     $this->authorize('create', Branch::class);

    //     $data = $request->validated();

    //     $branch = new Branch();
    //     $branch->fill($data);
    //     $branch->save();

    //     return Redirect::route('branches.index')->with('success', 'Branch created successfully.');
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Branch $branch): Response
    // {
    //     // $this->authorize('view', $branch);

    //     return Inertia::render('erp/branches/show', [
    //         'branch' => [
    //             'id' => $branch->id,
    //             'name' => $branch->name,
    //             'code' => $branch->code,
    //             'address' => $branch->address,
    //             'phone' => $branch->phone,
    //             'email' => $branch->email,
    //             'manager_name' => $branch->manager_name,
    //             'is_active' => $branch->is_active,
    //             'created_at' => $branch->created_at,
    //             'updated_at' => $branch->updated_at,
    //         ],
    //     ]);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Branch $branch): Response
    // {
    //     $this->authorize('update', $branch);

    //     return Inertia::render('erp/branches/edit', [
    //         'branch' => [
    //             'id' => $branch->id,
    //             'name' => $branch->name,
    //             'code' => $branch->code,
    //             'address' => $branch->address,
    //             'phone' => $branch->phone,
    //             'email' => $branch->email,
    //             'manager_name' => $branch->manager_name,
    //             'is_active' => $branch->is_active,
    //             'country_id' => $branch->country_id,
    //             'state_id' => $branch->state_id,
    //         ],
    //         'countries' => Country::orderBy('name')->get(),
    //         'states' => State::orderBy('name')->get(),
    //     ]);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(BranchUpdateRequest $request, Branch $branch): RedirectResponse
    // {
    //     $this->authorize('update', $branch);

    //     $data = $request->validated();

    //     $branch->fill($data);
    //     $branch->save();

    //     return Redirect::route('branches.index')->with('success', 'Branch updated successfully.');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Branch $branch)
    {
        try {
            // Check if branch has related records
            if ($branch->employees()->exists()) {
                return back()
                    ->with('error', 'Cannot delete branch. It has associated employees.');
            }
            
            if ($branch->agents()->exists()) {
                return back()
                    ->with('error', 'Cannot delete branch. It has associated agents.');
            }
            
            if ($branch->students()->exists()) {
                return back()
                    ->with('error', 'Cannot delete branch. It has associated students.');
            }

            $branch->delete();

            return redirect()
                ->route('branches.index')
                ->with('success', 'Branch deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting branch: ' . $e->getMessage());

            return back()
                ->with('error', 'Failed to delete branch. Please try again.');
        }
    }
}
