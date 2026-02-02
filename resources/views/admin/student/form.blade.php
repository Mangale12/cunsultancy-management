@extends('layouts.app')

@section('title', isset($student) ? 'Edit Student' : 'New Student')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i data-feather="check-circle" class="me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i data-feather="alert-circle" class="me-2"></i>
        <strong>Validation Error:</strong> Please check the form fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<x-page-header 
    :title="isset($student) ? 'Edit Student Profile' : 'Student Admission Form'"
    :subtitle="isset($student) ? 'Update student information and application details.' : 'Register a new student in the consultancy system.'"
    :actions="
        '<a href=\"' . route('students.index') . '\" class=\"btn btn-outline-secondary\">
            <i data-feather=\"arrow-left\" class=\"me-2\"></i> Back to Students
        </a>'
    "
/>

<x-card>
    <form action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          id="studentForm">
        @csrf
        @if(isset($student)) @method('PUT') @endif

        <!-- Progress Indicator -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Form Progress</h6>
                <span class="badge bg-primary" id="progressBadge">0%</span>
            </div>
            <div class="progress" style="height: 8px;">
                <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"></div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3 text-uppercase small fw-bold">
                    <i data-feather="user" class="me-2"></i>Personal Information
                </h6>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" 
                       name="first_name" 
                       class="form-control @error('first_name') is-invalid @enderror" 
                       value="{{ old('first_name', $student->first_name ?? '') }}" 
                       placeholder="Enter first name"
                       required>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" 
                       name="last_name" 
                       class="form-control @error('last_name') is-invalid @enderror" 
                       value="{{ old('last_name', $student->last_name ?? '') }}" 
                       placeholder="Enter last name"
                       required>
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" 
                       name="date_of_birth" 
                       class="form-control @error('date_of_birth') is-invalid @enderror" 
                       value="{{ isset($student) && $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : old('date_of_birth') }}"
                       max="{{ now()->subYears(16)->format('Y-m-d') }}">
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Profile Photo</label>
                <input type="file" 
                       name="image_path" 
                       class="form-control @error('image_path') is-invalid @enderror"
                       accept="image/*">
                @error('image_path')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Allowed formats: JPG, PNG, GIF. Max size: 2MB.</small>
                
                @if(isset($student) && $student->image_path)
                    <div class="mt-2">
                        <small class="text-muted">Current photo:</small><br>
                        <img src="{{ asset('storage/'.$student->image_path) }}" 
                             class="rounded-circle mt-1" 
                             width="60" height="60" 
                             alt="{{ $student->name }}">
                    </div>
                @endif
            </div>
        </div>

        <!-- Contact & Location Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3 text-uppercase small fw-bold">
                    <i data-feather="map-pin" class="me-2"></i>Contact & Location
                </h6>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Country <span class="text-danger">*</span></label>
                <select name="country_id" 
                        id="country_id" 
                        class="form-select @error('country_id') is-invalid @enderror" 
                        required>
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" 
                                {{ (isset($student) && $student->country_id == $country->id) ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                @error('country_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">State <span class="text-danger">*</span></label>
                <select name="state_id" 
                        id="state_id" 
                        class="form-select @error('state_id') is-invalid @enderror" 
                        required>
                    <option value="">Select State</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" 
                                {{ (isset($student) && $student->state_id == $state->id) ? 'selected' : '' }}>
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
                @error('state_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" 
                       name="phone" 
                       class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone', $student->phone ?? '') }}" 
                       placeholder="+1 (555) 123-4567"
                       required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" 
                       name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $student->email ?? '') }}" 
                       placeholder="student@example.com"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Consultancy Details Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3 text-uppercase small fw-bold">
                    <i data-feather="briefcase" class="me-2"></i>Consultancy Details
                </h6>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Assign Branch <span class="text-danger">*</span></label>
                <select name="branch_id" 
                        class="form-select @error('branch_id') is-invalid @enderror" 
                        required>
                    <option value="">Select Branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" 
                                {{ (isset($student) && $student->branch_id == $branch->id) ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Assigned Agent</label>
                <select name="agent_id" 
                        class="form-select @error('agent_id') is-invalid @enderror">
                    <option value="">Direct (No Agent)</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" 
                                {{ (isset($student) && $student->agent_id == $agent->id) ? 'selected' : '' }}>
                            {{ $agent->name }}
                        </option>
                    @endforeach
                </select>
                @error('agent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Desired Course <span class="text-danger">*</span></label>
                <select name="course_id" 
                        class="form-select @error('course_id') is-invalid @enderror" 
                        required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" 
                                {{ (isset($student) && $student->course_id == $course->id) ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Initial Status</label>
                <select name="status" 
                        class="form-select @error('status') is-invalid @enderror">
                    <option value="pending" {{ (isset($student) && $student->status == 'pending') ? 'selected' : '' }}>
                        Pending Inquiry
                    </option>
                    <option value="active" {{ (isset($student) && $student->status == 'active') ? 'selected' : '' }}>
                        Active Application
                    </option>
                    <option value="inactive" {{ (isset($student) && $student->status == 'inactive') ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-between align-items-center border-top pt-4">
            <div>
                <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                    <i data-feather="x" class="me-2"></i> Cancel
                </button>
            </div>
            <div>
                <button type="button" class="btn btn-outline-primary me-2" id="saveDraftBtn">
                    <i data-feather="save" class="me-2"></i> Save Draft
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i data-feather="check" class="me-2"></i> 
                    {{ isset($student) ? 'Update Student' : 'Create Student' }}
                </button>
            </div>
        </div>
    </form>
</x-card>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        feather.replace();
        
        // Form progress tracking
        function updateProgress() {
            const form = document.getElementById('studentForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            let filledCount = 0;
            
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    filledCount++;
                }
            });
            
            const progress = Math.round((filledCount / inputs.length) * 100);
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressBadge').textContent = progress + '%';
        }
        
        // Add event listeners to all form inputs
        const formInputs = document.querySelectorAll('#studentForm input, #studentForm select');
        formInputs.forEach(input => {
            input.addEventListener('input', updateProgress);
            input.addEventListener('change', updateProgress);
        });
        
        // Initialize progress on load
        updateProgress();
        
        // Phone number formatting
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.length <= 3) {
                        value = value;
                    } else if (value.length <= 6) {
                        value = '(' + value.slice(0, 3) + ') ' + value.slice(3);
                    } else {
                        value = '(' + value.slice(0, 3) + ') ' + value.slice(3, 6) + '-' + value.slice(6, 10);
                    }
                }
                e.target.value = value;
            });
        }
        
        // Save draft functionality
        const saveDraftBtn = document.getElementById('saveDraftBtn');
        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function() {
                const form = document.getElementById('studentForm');
                const formData = new FormData(form);
                
                // In a real application, you would save this to localStorage or send to server
                localStorage.setItem('studentDraft', JSON.stringify(Object.fromEntries(formData)));
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-info alert-dismissible fade show';
                alert.innerHTML = `
                    <i data-feather="info" class="me-2"></i>
                    Draft saved successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                form.parentElement.insertBefore(alert, form);
                feather.replace();
                
                // Auto-dismiss after 3 seconds
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            });
        }
        
        // Load draft if exists
        const savedDraft = localStorage.getItem('studentDraft');
        if (savedDraft && !document.querySelector('input[name="first_name"]').value) {
            const draft = JSON.parse(savedDraft);
            Object.keys(draft).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = draft[key];
                }
            });
            updateProgress();
        }
        
        // Form submission with loading state
        const form = document.getElementById('studentForm');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-feather="loader" class="me-2"></i> Saving...';
            
            // Re-enable after 5 seconds in case of issues
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i data-feather="check" class="me-2"></i> ' + 
                    (document.querySelector('input[name="_method"]')?.value === 'PUT' ? 'Update Student' : 'Create Student');
                feather.replace();
            }, 5000);
        });
        
        // Clear draft on successful submission
        @if(session('success'))
            localStorage.removeItem('studentDraft');
        @endif
    });
</script>
@endpush