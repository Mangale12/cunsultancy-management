<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $documentTypes = DocumentType::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->get('category'), function ($query, $category) {
                $query->where('category', $category);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('document-types/index', [
            'documentTypes' => $documentTypes,
            'filters' => $request->only(['search', 'category']),
            'categories' => DocumentType::select('category')->distinct()->pluck('category'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('document-types/create', [
            'categories' => [
                'academic' => 'Academic Documents',
                'financial' => 'Financial Documents',
                'identity' => 'Identity Documents',
                'medical' => 'Medical Documents',
                'visa' => 'Visa Documents',
                'language' => 'Language Proficiency',
                'experience' => 'Work Experience',
                'recommendation' => 'Recommendations',
                'personal' => 'Personal Statements',
                'travel' => 'Travel Documents',
                'accommodation' => 'Accommodation',
                'other' => 'Other Documents',
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:document_types',
            'description' => 'nullable|string',
            'category' => 'required|in:academic,financial,identity,medical,visa,language,experience,recommendation,personal,travel,accommodation,other',
            'is_required' => 'boolean',
            'has_expiry' => 'boolean',
            'allowed_file_types' => 'required|array|min:1',
            'allowed_file_types.*' => 'string|in:pdf,doc,docx,jpg,jpeg,png,txt',
            'max_file_size' => 'required|integer|min:1|max:10240',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['allowed_file_types'] = json_encode($validated['allowed_file_types']);

        DocumentType::create($validated);

        return redirect()->route('document-types.index')
            ->with('success', 'Document type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        return Inertia::render('document-types/show', [
            'documentType' => $documentType->load('documents'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        return Inertia::render('document-types/edit', [
            'documentType' => $documentType,
            'categories' => [
                'academic' => 'Academic Documents',
                'financial' => 'Financial Documents',
                'identity' => 'Identity Documents',
                'medical' => 'Medical Documents',
                'visa' => 'Visa Documents',
                'language' => 'Language Proficiency',
                'experience' => 'Work Experience',
                'recommendation' => 'Recommendations',
                'personal' => 'Personal Statements',
                'travel' => 'Travel Documents',
                'accommodation' => 'Accommodation',
                'other' => 'Other Documents',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:document_types,code,' . $documentType->id,
            'description' => 'nullable|string',
            'category' => 'required|in:academic,financial,identity,medical,visa,language,experience,recommendation,personal,travel,accommodation,other',
            'is_required' => 'boolean',
            'has_expiry' => 'boolean',
            'allowed_file_types' => 'required|array|min:1',
            'allowed_file_types.*' => 'string|in:pdf,doc,docx,jpg,jpeg,png,txt',
            'max_file_size' => 'required|integer|min:1|max:10240',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['allowed_file_types'] = json_encode($validated['allowed_file_types']);

        $documentType->update($validated);

        return redirect()->route('document-types.index')
            ->with('success', 'Document type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        if ($documentType->documents()->exists()) {
            return back()->with('error', 'Cannot delete document type that has associated documents.');
        }

        $documentType->delete();

        return redirect()->route('document-types.index')
            ->with('success', 'Document type deleted successfully.');
    }
}
