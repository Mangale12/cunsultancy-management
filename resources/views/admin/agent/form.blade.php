@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ isset($agent) ? 'Edit' : 'Create' }} Agent</h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($agent) ? route('agents.update', $agent->id) : route('agents.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($agent)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $agent->name ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $agent->code ?? '') }}" placeholder="AG-001" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Assign Branch</label>
                        <select name="branch_id" class="form-select" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (isset($agent) && $agent->branch_id == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Parent Agent (Optional)</label>
                        <select name="parent_agent_id" class="form-select">
                            <option value="">No Parent (Independent)</option>
                            @foreach($parentAgents as $p)
                                <option value="{{ $p->id }}" {{ (isset($agent) && $agent->parent_agent_id == $p->id) ? 'selected' : '' }}>{{ $p->name }} ({{ $p->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $agent->email ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $agent->phone ?? '') }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Agent Photo</label>
                        <input type="file" name="image_path" class="form-control">
                        @if(isset($agent) && $agent->image_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$agent->image_path) }}" width="100" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4 border-top pt-3 d-flex justify-content-between">
                    <a href="{{ route('agents.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-5">Save Agent</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection