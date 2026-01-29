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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Branch::class);

        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        $branches = Branch::visibleTo($request->user())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
                      ->orWhere('manager_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('erp/branches/index', [
            'branches' => $branches,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Branch::class);

        return Inertia::render('erp/branches/create', [
            'countries' => Country::orderBy('name')->get(),
            'states' => State::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Branch::class);

        $data = $request->validated();

        $branch = new Branch();
        $branch->fill($data);
        $branch->save();

        return Redirect::route('branches.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch): Response
    {
        // $this->authorize('view', $branch);

        return Inertia::render('erp/branches/show', [
            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
                'code' => $branch->code,
                'address' => $branch->address,
                'phone' => $branch->phone,
                'email' => $branch->email,
                'manager_name' => $branch->manager_name,
                'is_active' => $branch->is_active,
                'created_at' => $branch->created_at,
                'updated_at' => $branch->updated_at,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch): Response
    {
        $this->authorize('update', $branch);

        return Inertia::render('erp/branches/edit', [
            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
                'code' => $branch->code,
                'address' => $branch->address,
                'phone' => $branch->phone,
                'email' => $branch->email,
                'manager_name' => $branch->manager_name,
                'is_active' => $branch->is_active,
                'country_id' => $branch->country_id,
                'state_id' => $branch->state_id,
            ],
            'countries' => Country::orderBy('name')->get(),
            'states' => State::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchUpdateRequest $request, Branch $branch): RedirectResponse
    {
        $this->authorize('update', $branch);

        $data = $request->validated();

        $branch->fill($data);
        $branch->save();

        return Redirect::route('branches.index')->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Branch $branch): RedirectResponse
    {
        $this->authorize('delete', $branch);

        try {
            // Check if branch exists
            if (!$branch) {
                Log::error("Branch not found for deletion");
                return Redirect::route('branches.index')->with('error', 'Branch not found.');
            }

            // Log the branch ID being deleted
            Log::info("Attempting to delete branch ID: {$branch->id}");

            // Check for related records
            $relatedCount = 0;
            $relatedModels = [];

            // Check employees
            $employeeCount = $branch->employees()->count();
            if ($employeeCount > 0) {
                $relatedCount += $employeeCount;
                $relatedModels[] = "{$employeeCount} employees";
            }

            // Check students
            $studentCount = $branch->students()->count();
            if ($studentCount > 0) {
                $relatedCount += $studentCount;
                $relatedModels[] = "{$studentCount} students";
            }

            if ($relatedCount > 0) {
                $relatedList = implode(', ', $relatedModels);
                Log::warning("Cannot delete branch {$branch->id}: has {$relatedCount} related records ({$relatedList})");
                return Redirect::route('branches.index')
                    ->with('error', "Cannot delete branch '{$branch->name}' because it has {$relatedCount} related records ({$relatedList}). Please delete or reassign these records first.");
            }

            // Delete the branch
            $branchName = $branch->name;
            $branchId = $branch->id;
            
            if ($branch->delete()) {
                Log::info("Successfully deleted branch ID: {$branchId} ({$branchName})");
                return Redirect::route('branches.index')
                    ->with('success', "Branch '{$branchName}' deleted successfully.");
            } else {
                Log::error("Failed to delete branch ID: {$branchId}");
                return Redirect::route('branches.index')
                    ->with('error', 'Failed to delete branch. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error("Error deleting branch: " . $e->getMessage(), [
                'branch_id' => $branch->id ?? 'unknown',
                'exception' => $e,
            ]);

            return Redirect::route('branches.index')
                ->with('error', 'An error occurred while deleting the branch. Please try again.');
        }
    }
}
