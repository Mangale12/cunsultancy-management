@extends('layouts.app')

@section('title', 'Universities')

@section('content')
<!-- Success Message -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i data-feather="check-circle" class="me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<x-page-header 
    title="University Management"
    subtitle="Manage educational institutions and their partnered programs for student applications."
    :actions="
        '<a href=\"' . route('universities.create') . '\" class=\"btn btn-primary\">
            <i data-feather=\"book-open\" class=\"me-2\"></i> Add New University
        </a>'
    "
/>

<!-- Search and Filter Section -->
<x-card class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-label">Search Universities</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Search by university name or country..." 
                       id="universitySearch">
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-grid gap-2 d-md-flex">
                <button type="button" class="btn btn-outline-secondary" id="clearUniversitySearch">
                    <i data-feather="x" class="me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-outline-primary" id="exportUniversities">
                    <i data-feather="download" class="me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</x-card>

<!-- Universities Table -->
<x-card>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="universityTable">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>University Name</th>
                    <th class="d-none d-md-table-cell">Country</th>
                    <th class="d-none d-lg-table-cell">Location</th>
                    <th class="d-none d-md-table-cell">Status</th>
                    <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($universities as $university)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark fw-normal">{{ $loop->iteration }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i data-feather="book-open" class="text-primary" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $university->name }}</div>
                                @if($university->code)
                                    <small class="text-muted">Code: {{ $university->code }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-info text-dark">{{ $university->country->name ?? 'N/A' }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @if($university->city)
                            <div class="small">
                                <div class="text-truncate" style="max-width: 150px;">
                                    <i data-feather="map-pin" style="width: 12px; height: 12px;"></i> 
                                    {{ $university->city }}
                                </div>
                                @if($university->state)
                                    <div class="text-muted">{{ $university->state }}</div>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td class="text-center">
                        <x-action-buttons 
                            :edit-route="'universities.edit'"
                            :show-route="'universities.show'"
                            :delete-route="'universities.destroy'"
                            :edit-id="$university->id"
                            :show-id="$university->id"
                            :delete-id="$university->id"
                            delete-confirm="Are you sure you want to delete this university? This will affect all associated courses and applications."
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i data-feather="book-open" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3 mb-2">No universities found</h5>
                            <p class="mb-3">Start by adding your first university to offer educational programs.</p>
                            <a href="{{ route('universities.create') }}" class="btn btn-primary">
                                <i data-feather="book-open" class="me-2"></i>Add First University
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($universities, 'hasPages') && $universities->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $universities->firstItem() }} to {{ $universities->lastItem() }} of {{ $universities->total() }} universities
            </small>
            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $universities->links() }}
            </div>
        </div>
    </div>
    @endif
</x-card>

<!-- Statistics Cards -->
<div class="row g-4 mt-2">
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="book-open" class="text-primary mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $universities->count() }}</h4>
                <small class="text-muted">Total Universities</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="globe" class="text-success mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $universities->pluck('country_id')->unique()->count() }}</h4>
                <small class="text-muted">Countries Represented</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="book" class="text-info mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $universities->sum(function($u) { return $u->courses_count ?? 0; }) }}</h4>
                <small class="text-muted">Courses Offered</small>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        feather.replace();
        
        // University search functionality
        const universitySearch = document.getElementById('universitySearch');
        const clearUniversitySearch = document.getElementById('clearUniversitySearch');
        const universityTable = document.getElementById('universityTable');
        const rows = universityTable.querySelectorAll('tbody tr');
        
        if (universitySearch) {
            universitySearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchText);
                    row.style.display = isVisible ? '' : 'none';
                });
                
                // Update empty state visibility
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyRow = universityTable.querySelector('tr:has(.text-muted)');
                if (emptyRow) {
                    emptyRow.style.display = visibleRows.length === 0 && rows.length > 1 ? '' : 'none';
                }
            });
        }
        
        // Clear search
        if (clearUniversitySearch) {
            clearUniversitySearch.addEventListener('click', function() {
                if (universitySearch) universitySearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Export functionality
        const exportUniversities = document.getElementById('exportUniversities');
        if (exportUniversities) {
            exportUniversities.addEventListener('click', function() {
                // Simple CSV export
                let csv = 'University Name,Country,City,State,Status\n';
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = row.querySelectorAll('td');
                        const name = cells[1]?.textContent.trim() || '';
                        csv += `"${name}","","","","","Active"\n`;
                    }
                });
                
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'universities_export.csv';
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i data-feather="check-circle" class="me-2"></i>
                    Universities exported successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                feather.replace();
                
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            });
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (universitySearch) universitySearch.focus();
            }
            
            if (e.key === 'Escape' && universitySearch && universitySearch.value) {
                universitySearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
</script>
@endpush
