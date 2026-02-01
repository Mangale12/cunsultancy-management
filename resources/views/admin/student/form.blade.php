@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">{{ isset($student) ? 'Edit Student Profile' : 'Student Admission Form' }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($student)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-4 border-end">
                        <h6 class="text-muted mb-3 text-uppercase small fw-bold">Personal Details</h6>
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ isset($student) ? $student->date_of_birth->format('Y-m-d') : '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="image_path" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4 border-end">
                        <h6 class="text-muted mb-3 text-uppercase small fw-bold">Contact & Location</h6>
                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <select name="country_id" id="country_id" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ (isset($student) && $student->country_id == $country->id) ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">State</label>
                            <select name="state_id" id="state_id" class="form-select" required>
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ (isset($student) && $student->state_id == $state->id) ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $student->email ?? '') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-muted mb-3 text-uppercase small fw-bold">Consultancy Details</h6>
                        <div class="mb-3">
                            <label class="form-label">Assign Branch</label>
                            <select name="branch_id" class="form-select" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ (isset($student) && $student->branch_id == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assigned Agent</label>
                            <select name="agent_id" class="form-select">
                                <option value="">Direct (No Agent)</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ (isset($student) && $student->agent_id == $agent->id) ? 'selected' : '' }}>{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Desired Course</label>
                            <select name="course_id" class="form-select" required>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ (isset($student) && $student->course_id == $course->id) ? 'selected' : '' }}>{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Initial Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ (isset($student) && $student->status == 'pending') ? 'selected' : '' }}>Pending Inquiry</option>
                                <option value="active" {{ (isset($student) && $student->status == 'active') ? 'selected' : '' }}>Active Application</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end border-top pt-3">
                    <a href="{{ route('students.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Save Student Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection