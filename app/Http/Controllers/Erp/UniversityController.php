<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UniversityController extends Controller
{

    public function index()
    {
        $universities = University::all();
        return view('admin.university.index', compact('universities'));
    }

    public function create()
    {
        $countries = Country::all();
        $states = State::all();
        return view('admin.university.form', compact('countries', 'states'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:universities,code',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'image_path' => 'nullable|string|max:255',
        ]);

        University::create($validated);

        return redirect()->route('universities.index')
            ->with('success', 'University created successfully.');
    }
    
    public function show(University $university)
    {
        // Load university with related data
        $university->load(['country', 'state', 'courses']);
        
        return view('admin.university.show', compact('university'));
    }
    
    public function edit(University $university)
    {
        $countries = Country::all();
        $states = State::all();
        return view('admin.university.form', compact('university', 'countries', 'states'));
    }
    
    public function update(Request $request, University $university){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:universities,code,' . $university->id,
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'image_path' => 'nullable|string|max:255',
        ]);

        $university->update($validated);

        return redirect()->route('universities.index')
            ->with('success', 'University updated successfully.');
    }
    
    public function destroy(University $university)
    {
        $university->delete();

        return redirect()->route('universities.index')
            ->with('success', 'University deleted successfully.');
    }
    // public function index(Request $request): Response
    // {
    //     $search = $request->get('search');
    //     $country = $request->get('country');
    //     $state = $request->get('state');
    //     $perPage = $request->get('per_page', 10);

    //     $query = University::with(['country', 'state'])
    //         ->when($search, function ($query, $search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                   ->orWhere('code', 'like', "%{$search}%");
    //             });
    //         })
    //         ->when($country, function ($query, $country) {
    //             $query->where('country_id', $country);
    //         })
    //         ->when($state, function ($query, $state) {
    //             $query->where('state_id', $state);
    //         })
    //         ->orderBy('created_at', 'desc');

    //     $universities = $query->paginate($perPage);
    //     $countries = Country::all();
    //     $states = State::all();

    //     return Inertia::render('universities/index', [
    //         'universities' => $universities,
    //         'countries' => $countries,
    //         'states' => $states,
    //         'filters' => [
    //             'search' => $search,
    //             'country' => $country,
    //             'state' => $state,
    //             'per_page' => $perPage,
    //         ],
    //     ]);
    // }

    // public function create(): Response
    // {
    //     $countries = Country::all();
    //     $states = State::all();

    //     return Inertia::render('universities/create', [
    //         'countries' => $countries,
    //         'states' => $states,
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'code' => 'required|string|max:50|unique:universities,code',
    //         'country_id' => 'required|exists:countries,id',
    //         'state_id' => 'required|exists:states,id',
    //         'image_path' => 'nullable|string|max:255',
    //     ]);

    //     University::create($validated);

    //     return redirect()->route('universities.index')
    //         ->with('success', 'University created successfully.');
    // }

    // public function show(University $university): Response
    // {
    //     $university->load(['country', 'state', 'courses']);

    //     return Inertia::render('universities/show', [
    //         'university' => $university,
    //     ]);
    // }

    // public function edit(University $university): Response
    // {
    //     $countries = Country::all();
    //     $states = State::all();

    //     return Inertia::render('universities/edit', [
    //         'university' => $university,
    //         'countries' => $countries,
    //         'states' => $states,
    //     ]);
    // }

    // public function update(Request $request, University $university)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'code' => 'required|string|max:50|unique:universities,code,' . $university->id,
    //         'country_id' => 'required|exists:countries,id',
    //         'state_id' => 'required|exists:states,id',
    //         'image_path' => 'nullable|string|max:255',
    //     ]);

    //     $university->update($validated);

    //     return redirect()->route('universities.index')
    //         ->with('success', 'University updated successfully.');
    // }

    // public function destroy(University $university)
    // {
    //     $university->delete();

    //     return redirect()->route('universities.index')
    //         ->with('success', 'University deleted successfully.');
    // }
}
