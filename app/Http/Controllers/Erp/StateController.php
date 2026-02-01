<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Erp\StateStoreRequest;
use App\Http\Requests\Erp\StateUpdateRequest;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class StateController extends Controller
{


    public function index()
    {
        $states = State::all();
        return view('admin.states.index', compact('states'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('admin.states.form', compact('countries'));
    }

    public function store(StateStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $state = State::create($data);
        return redirect()->route('states.index')->with('success', 'State created successfully.');
    }

    public function edit(State $state)
    {
        $countries = Country::all();
        return view('admin.states.edit', compact('state', 'countries'));
    }

    public function update(StateUpdateRequest $request, State $state): RedirectResponse
    {
        $data = $request->validated();
        $state->update($data);
        return redirect()->route('states.index')->with('success', 'State updated successfully.');
    }

    public function destroy(State $state): RedirectResponse
    {
        $state->delete();
        return redirect()->route('states.index')->with('success', 'State deleted successfully.');
    }
    // public function index(Request $request): Response
    // {
    //     // $this->authorize('viewAny', State::class);

    //     $search = $request->string('search')->toString();
    //     $countryId = $request->integer('country_id');

    //     $states = State::query()
    //         ->when($search !== '', function ($query) use ($search) {
    //             $query->where('name', 'like', "%{$search}%");
    //         })
    //         ->when($countryId > 0, function ($query) use ($countryId) {
    //             $query->where('country_id', $countryId);
    //         })
    //         ->with('country')
    //         ->paginate(10)
    //         ->withQueryString();

    //     $countries = Country::orderBy('name')->get();

    //     return Inertia::render('erp/states/index', [
    //         'states' => $states,
    //         'filters' => [
    //             'search' => $search,
    //             'country_id' => $countryId,
    //         ],
    //         'countries' => $countries,
    //     ]);
    // }

    // public function create(): Response
    // {
    //     // $this->authorize('create', State::class);

    //     $countries = Country::orderBy('name')->get();

    //     return Inertia::render('erp/states/create', [
    //         'countries' => $countries,
    //     ]);
    // }

    // public function store(StateStoreRequest $request): RedirectResponse
    // {
    //     // $this->authorize('create', State::class);

    //     $data = $request->validated();

    //     $state = new State();
    //     $state->fill($data);
    //     $state->save();

    //     return to_route('states.index')->with('success', 'State created successfully.');
    // }

    // public function show(State $state): Response
    // {
    //     // $this->authorize('view', $state);

    //     $state->load('country');

    //     return Inertia::render('erp/states/show', [
    //         'state' => [
    //             'id' => $state->id,
    //             'name' => $state->name,
    //             'code' => $state->code,
    //             'country' => [
    //                 'id' => $state->country->id,
    //                 'name' => $state->country->name,
    //             ],
    //             'created_at' => $state->created_at->format('Y-m-d H:i:s'),
    //             'updated_at' => $state->updated_at->format('Y-m-d H:i:s'),
    //         ],
    //     ]);
    // }

    // public function edit(State $state): Response
    // {
    //     // $this->authorize('update', $state);

    //     $state->load('country');
    //     $countries = Country::orderBy('name')->get();

    //     return Inertia::render('erp/states/edit', [
    //         'state' => [
    //             'id' => $state->id,
    //             'name' => $state->name,
    //             'code' => $state->code,
    //             'country_id' => $state->country_id,
    //         ],
    //         'countries' => $countries,
    //     ]);
    // }

    // public function update(StateUpdateRequest $request, State $state): RedirectResponse
    // {
    //     // $this->authorize('update', $state);

    //     $data = $request->validated();

    //     $state->fill($data);
    //     $state->save();

    //     return to_route('states.index')->with('success', 'State updated successfully.');
    // }

    // public function destroy(Request $request, State $state): RedirectResponse
    // {
    //     $this->authorize('delete', $state);

    //     try {
    //         // Check if state has related records
    //         // Branches have nullOnDelete, but we'll check for clarity
    //         if ($state->branches()->withoutGlobalScope('access')->exists()) {
    //             return to_route('states.index')->with('error', 'Cannot delete state: it has associated branches.');
    //         }
    //         // Check other potential relationships
    //         if ($state->universities()->withoutGlobalScope('access')->exists()) {
    //             return to_route('states.index')->with('error', 'Cannot delete state: it has associated universities.');
    //         }
    //         if ($state->students()->withoutGlobalScope('access')->exists()) {
    //             return to_route('states.index')->with('error', 'Cannot delete state: it has associated students.');
    //         }

    //         $state->delete();

    //         return to_route('states.index')->with('success', 'State deleted successfully.');
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         // Handle foreign key constraint violations
    //         if ($e->getCode() == '23000') {
    //             return to_route('states.index')->with('error', 'Cannot delete state: it is referenced by other records.');
    //         }
    //         // Handle other database errors
    //         return to_route('states.index')->with('error', 'An error occurred while deleting the state.');
    //     } catch (\Exception $e) {
    //         Log::error("General error deleting state {$state->id}: " . $e->getMessage());
    //         return to_route('states.index')->with('error', 'An unexpected error occurred.');
    //     }
    // }
}
