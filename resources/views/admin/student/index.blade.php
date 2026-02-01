@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Enrollment</h1>
        <a href="{{ route('students.create') }}" class="btn btn-primary shadow-sm">
            <i data-feather="user-plus" class="me-1"></i> New Registration
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Contact</th>
                            <th>Branch/Agent</th>
                            <th>Applied Course</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $student->image_path ? asset('storage/'.$student->image_path) : 'https://ui-avatars.com/api/?name='.$student->name }}" 
                                         class="rounded-circle me-3" width="40" height="40">
                                    <div>
                                        <div class="fw-bold">{{ $student->name }}</div>
                                        <small class="text-muted">ID: #STU-{{ $student->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i data-feather="mail" style="width:12px"></i> {{ $student->email }}</div>
                                <div><i data-feather="phone" style="width:12px"></i> {{ $student->phone }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $student->branch->name ?? 'N/A' }}</span><br>
                                <small class="text-muted">Agent: {{ $student->agent->name ?? 'Direct' }}</small>
                            </td>
                            <td>{{ $student->course->name ?? 'Not Assigned' }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $student->status == 'active' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-light border"><i data-feather="edit"></i></a>
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-light border"><i data-feather="eye"></i></a>
                                <button class="btn btn-sm btn-light border text-danger"><i data-feather="trash-2"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection