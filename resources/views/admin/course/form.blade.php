@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
            <li class="breadcrumb-item active">{{ isset($course) ? 'Edit' : 'Create' }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 font-weight-bold text-primary">
                <i data-feather="book-open" class="me-2"></i>{{ isset($course) ? 'Edit Course Details' : 'Add New Course' }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($course) ? route('courses.update', $course->id) : route('courses.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($course)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Program/Course Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $course->name ?? '') }}" placeholder="e.g. MSc in Data Science" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Academic Level</label>
                        <select name="level" class="form-select" required>
                            <option value="">Select Level</option>
                            @foreach(['Foundation', 'Undergraduate', 'Postgraduate', 'PHD', 'Diploma'] as $lvl)
                                <option value="{{ $lvl }}" {{ (old('level', $course->level ?? '') == $lvl) ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">University</label>
                        <select name="university_id" class="form-select" required>
                            <option value="">Select University</option>
                            @foreach($universities as $uni)
                                <option value="{{ $uni->id }}" {{ (old('university_id', $course->university_id ?? '') == $uni->id) ? 'selected' : '' }}>{{ $uni->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Duration (Months)</label>
                        <div class="input-group">
                            <input type="number" name="duration_months" class="form-control" value="{{ old('duration_months', $course->duration_months ?? '') }}" required>
                            <span class="input-group-text">Months</span>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Currency</label>
                        <select name="currency" class="form-select" required>
                            @foreach(['USD', 'GBP', 'EUR', 'AUD', 'CAD'] as $curr)
                                <option value="{{ $curr }}" {{ (old('currency', $course->currency ?? 'USD') == $curr) ? 'selected' : '' }}>{{ $curr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Tuition Fee</label>
                        <div class="input-group">
                            <span class="input-group-text">$ / Amount</span>
                            <input type="number" step="0.01" name="tuition_fee" class="form-control" value="{{ old('tuition_fee', $course->tuition_fee ?? '') }}" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Program Banner/Image</label>
                        <input type="file" name="image_path" class="form-control">
                        @if(isset($course) && $course->image_path)
                            <img src="{{ asset('storage/'.$course->image_path) }}" width="150" class="mt-2 img-thumbnail">
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4 border-top pt-3">
                    <a href="{{ route('courses.index') }}" class="btn btn-light shadow-sm">
                        <i data-feather="x"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                        <i data-feather="check-circle" class="me-1"></i> {{ isset($course) ? 'Update' : 'Create' }} Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection