<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $countries = Country::when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin/countries/index', compact('countries'));
    }

    public function create()
    {
        return view('admin/countries/form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:countries'],
            'code' => ['nullable', 'string', 'max:3', 'unique:countries'],
            'currency' => ['nullable', 'string', 'max:10'],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        try {
            Country::create($validated);

            return redirect()
                ->route('countries.index')
                ->with('success', 'Country created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating country: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create country. Please try again.');
        }
    }

    public function edit(Country $country)
    {
        return view('admin/countries/form', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:countries,name,' . $country->id],
            'code' => ['nullable', 'string', 'max:3', 'unique:countries,code,' . $country->id],
            'currency' => ['nullable', 'string', 'max:10'],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        try {
            $country->update($validated);

            return redirect()
                ->route('countries.index')
                ->with('success', 'Country updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating country: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update country. Please try again.');
        }
    }

   
}
