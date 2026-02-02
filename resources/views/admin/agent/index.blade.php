@extends('layouts.app')

@section('title', 'Agents')

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
    title="Agent Management"
    subtitle="Manage agents and their assigned branches for student recruitment."
    :actions="
        '<a href=\"' . route('agents.create') . '\" class=\"btn btn-primary\">
            <i data-feather=\"user-plus\" class=\"me-2\"></i> Add New Agent
        </a>'
    "
/>

<!-- Search and Filter Section -->
<x-card class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-label">Search Agents</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Search by name, code, or email..." 
                       id="agentSearch">
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-grid gap-2 d-md-flex">
                <button type="button" class="btn btn-outline-secondary" id="clearAgentSearch">
                    <i data-feather="x" class="me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-outline-primary" id="exportAgents">
                    <i data-feather="download" class="me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</x-card>

<!-- Agents Table -->
<x-card>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="agentTable">
            <thead>
                <tr>
                    <th style="width: 80px;">Code</th>
                    <th>Agent Name</th>
                    <th class="d-none d-md-table-cell">Branch</th>
                    <th class="d-none d-lg-table-cell">Parent Agent</th>
                    <th class="d-none d-md-table-cell">Contact</th>
                    <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agents as $agent)
                <tr>
                    <td>
                        <span class="badge bg-secondary">{{ $agent->code }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $agent->image_path ? asset('storage/'.$agent->image_path) : 'https://ui-avatars.com/api/?name='.urlencode($agent->name).'&background=0d6efd&color=fff' }}" 
                                 class="rounded-circle me-3" width="40" height="40" alt="{{ $agent->name }}">
                            <div>
                                <div class="fw-semibold">{{ $agent->name }}</div>
                                <small class="text-muted">ID: #AG-{{ str_pad($agent->id, 3, '0', STR_PAD_LEFT) }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-info text-dark">{{ $agent->branch->name ?? 'N/A' }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="text-muted">{{ $agent->parent->name ?? 'Main Agent' }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <div class="small">
                            <div class="text-truncate" style="max-width: 150px;">
                                <i data-feather="mail" style="width: 12px; height: 12px;"></i> 
                                {{ $agent->email }}
                            </div>
                            <div class="text-muted">
                                <i data-feather="phone" style="width: 12px; height: 12px;"></i> 
                                {{ $agent->phone }}
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <x-action-buttons 
                            :edit-route="'agents.edit'"
                            :show-route="'agents.show'"
                            :delete-route="'agents.destroy'"
                            :edit-id="$agent->id"
                            :show-id="$agent->id"
                            :delete-id="$agent->id"
                            delete-confirm="Are you sure you want to delete this agent? This action cannot be undone."
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i data-feather="user-check" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3 mb-2">No agents found</h5>
                            <p class="mb-3">Start by adding your first agent to manage student recruitment.</p>
                            <a href="{{ route('agents.create') }}" class="btn btn-primary">
                                <i data-feather="user-plus" class="me-2"></i>Add First Agent
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($agents, 'hasPages') && $agents->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $agents->firstItem() }} to {{ $agents->lastItem() }} of {{ $agents->total() }} agents
            </small>
            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $agents->links() }}
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
                <i data-feather="user-check" class="text-primary mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $agents->count() }}</h4>
                <small class="text-muted">Total Agents</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="briefcase" class="text-success mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $agents->whereNotNull('branch_id')->count() }}</h4>
                <small class="text-muted">Assigned to Branches</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="users" class="text-info mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $agents->sum(function($a) { return $a->students_count ?? 0; }) }}</h4>
                <small class="text-muted">Total Students</small>
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
        
        // Agent search functionality
        const agentSearch = document.getElementById('agentSearch');
        const clearAgentSearch = document.getElementById('clearAgentSearch');
        const agentTable = document.getElementById('agentTable');
        const rows = agentTable.querySelectorAll('tbody tr');
        
        if (agentSearch) {
            agentSearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchText);
                    row.style.display = isVisible ? '' : 'none';
                });
                
                // Update empty state visibility
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyRow = agentTable.querySelector('tr:has(.text-muted)');
                if (emptyRow) {
                    emptyRow.style.display = visibleRows.length === 0 && rows.length > 1 ? '' : 'none';
                }
            });
        }
        
        // Clear search
        if (clearAgentSearch) {
            clearAgentSearch.addEventListener('click', function() {
                if (agentSearch) agentSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Export functionality
        const exportAgents = document.getElementById('exportAgents');
        if (exportAgents) {
            exportAgents.addEventListener('click', function() {
                // Simple CSV export
                let csv = 'Agent Code,Name,Branch,Email,Phone\n';
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = row.querySelectorAll('td');
                        const code = cells[0]?.textContent.trim() || '';
                        const name = cells[1]?.textContent.trim() || '';
                        csv += `"${code}","${name}","","",""\n`;
                    }
                });
                
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'agents_export.csv';
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i data-feather="check-circle" class="me-2"></i>
                    Agents exported successfully!
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
                if (agentSearch) agentSearch.focus();
            }
            
            if (e.key === 'Escape' && agentSearch && agentSearch.value) {
                agentSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
</script>
@endpush