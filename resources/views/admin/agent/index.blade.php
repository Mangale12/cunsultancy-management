@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Agent Management</h1>
        <a href="{{ route('agents.create') }}" class="btn btn-primary">
            <i data-feather="plus"></i> Add New Agent
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Agent Name</th>
                            <th>Branch</th>
                            <th>Parent Agent</th>
                            <th>Contact</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $agent->code }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $agent->image_path ? asset('storage/'.$agent->image_path) : 'https://ui-avatars.com/api/?name='.$agent->name }}" 
                                         class="rounded-circle me-2" width="35" height="35">
                                    <strong>{{ $agent->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $agent->branch->name ?? 'N/A' }}</td>
                            <td>{{ $agent->parent->name ?? 'None (Main)' }}</td>
                            <td>
                                <small>{{ $agent->email }}</small><br>
                                <small class="text-muted">{{ $agent->phone }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-outline-primary"><i data-feather="edit-2"></i></a>
                                <form action="{{ route('agents.destroy', $agent->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this agent?')"><i data-feather="trash-2"></i></button>
                                </form>
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