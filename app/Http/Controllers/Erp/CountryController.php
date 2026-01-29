<?php

namespace App\Http\Controllers\Erp;

use App\Http\Requests\Erp\CountryStoreRequest;
use App\Http\Requests\Erp\CountryUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CountryController extends Controller
{
    public function index(Request $request): Response
    {
        // $this->authorize('viewAny', Country::class);

        $search = $request->string('search')->toString();

        $countries = Country::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $countries->getCollection()->transform(function (Country $country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
            ];
        });

        return Inertia::render('erp/countries/index', [
            'countries' => $countries,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        // $this->authorize('create', Country::class);

        return Inertia::render('erp/countries/create');
    }

    public function store(CountryStoreRequest $request): RedirectResponse
    {
        // $this->authorize('create', Country::class);

        $data = $request->validated();

        Country::create($data);

        return to_route('countries.index')->with('success', 'Country created successfully.');
    }

    public function show(Country $country): Response
    {
        // $this->authorize('view', $country);

        return Inertia::render('erp/countries/show', [
            'country' => [
                'id' => $country->id,
                'name' => $country->name,
            ],
        ]);
    }

    public function edit(Country $country): Response
    {
        // $this->authorize('update', $country);

        return Inertia::render('erp/countries/edit', [
            'country' => [
                'id' => $country->id,
                'name' => $country->name,
            ],
        ]);
    }

    public function update(CountryUpdateRequest $request, Country $country): RedirectResponse
    {
        // $this->authorize('update', $country);

        $data = $request->validated();

        $country->update($data);

        return to_route('countries.index')->with('success', 'Country updated successfully.');
    }

    public function destroy(Request $request, Country $country): RedirectResponse
    {
        // $this->authorize('delete', $country);

        try {
            // Check if country exists
            if (!$country) {
                \Log::error("Country not found for deletion");
                return to_route('countries.index')->with('error', 'Country not found.');
            }

            // Log the country ID being deleted
            \Log::info("Attempting to delete country ID: {$country->id}");
            
            // Check if country has related records
            // Branches have restrictOnDelete, so we must check them first
            \Log::info("Checking branches for country {$country->id}");
            try {
                if ($country->branches()->withoutGlobalScope('access')->exists()) {
                    \Log::info("Country {$country->id} has branches, cannot delete");
                    return to_route('countries.index')->with('error', 'Cannot delete country: it has associated branches.');
                }
                \Log::info("No branches found for country {$country->id}");
            } catch (\Exception $e) {
                \Log::error("Error checking branches for country {$country->id}: " . $e->getMessage());
                return to_route('countries.index')->with('error', 'Error checking country relationships.');
            }
            
            // States have cascadeOnDelete, but we'll check for clarity
            \Log::info("Checking states for country {$country->id}");
            if ($country->states()->withoutGlobalScope('access')->exists()) {
                \Log::info("Country {$country->id} has states, cannot delete");
                return to_route('countries.index')->with('error', 'Cannot delete country: it has associated states.');
            }
            \Log::info("No states found for country {$country->id}");
            
            // Check other potential relationships
            \Log::info("Checking universities for country {$country->id}");
            if ($country->universities()->withoutGlobalScope('access')->exists()) {
                \Log::info("Country {$country->id} has universities, cannot delete");
                return to_route('countries.index')->with('error', 'Cannot delete country: it has associated universities.');
            }
            \Log::info("No universities found for country {$country->id}");
            
            \Log::info("Checking students for country {$country->id}");
            if ($country->students()->withoutGlobalScope('access')->exists()) {
                \Log::info("Country {$country->id} has students, cannot delete");
                return to_route('countries.index')->with('error', 'Cannot delete country: it has associated students.');
            }
            \Log::info("No students found for country {$country->id}");

            \Log::info("No related records found for country {$country->id}, proceeding with deletion");

            $country->delete();

            \Log::info("Country {$country->id} deleted successfully");

            return to_route('countries.index')->with('success', 'Country deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error("Database error deleting country {$country->id}: " . $e->getMessage());
            // Handle foreign key constraint violations
            if ($e->getCode() == '23000') {
                return to_route('countries.index')->with('error', 'Cannot delete country: it is referenced by other records.');
            }
            // Handle other database errors
            return to_route('countries.index')->with('error', 'An error occurred while deleting the country.');
        } catch (\Exception $e) {
            \Log::error("General error deleting country {$country->id}: " . $e->getMessage());
            return to_route('countries.index')->with('error', 'An unexpected error occurred.');
        }
    }
}
