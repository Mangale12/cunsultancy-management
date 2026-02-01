@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Add Course</h6>
            <div>
                <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus" class="me-1" style="width: 14px; height: 14px;"></i> Add Course
                </a>
            </div>
        </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="courseTable">
                <thead class="table-light">
                    <tr>
                        <th>S.N</th>
                        <th>Course Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $course->name }}</strong></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                </a>
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No courses found. <a href="{{ route('courses.create') }}">Add a new course</a>.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Feather Icons
    document.addEventListener('DOMContentLoaded', function() {
        if (feather) {
            feather.replace({ width: 14, height: 14 });
        }

        // Table search functionality
        const tableSearch = document.getElementById('tableSearch');
        if (tableSearch) {
            tableSearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                const rows = document.querySelectorAll('#courseTable tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchText) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush
@endsection
