<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class StudentApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'university_id',
        'course_id',
        'application_status',
        'application_date',
        'submission_deadline',
        'admission_deadline',
        'visa_status',
        'visa_application_date',
        'visa_interview_date',
        'visa_approval_date',
        'pre_departure_status',
        'tuition_fee',
        'scholarship_amount',
        'notes',
    ];

    protected $casts = [
        'application_date' => 'date',
        'submission_deadline' => 'date',
        'admission_deadline' => 'date',
        'visa_application_date' => 'date',
        'visa_interview_date' => 'date',
        'visa_approval_date' => 'date',
        'tuition_fee' => 'decimal:2',
        'scholarship_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('application_status', $status);
    }

    public function scopeByVisaStatus($query, $status)
    {
        return $query->where('visa_status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('application_status', ['draft', 'submitted', 'under_review', 'admitted']);
    }

    public function getApplicationStatusBadgeAttribute(): array
    {
        return match($this->application_status) {
            'draft' => ['label' => 'Draft', 'variant' => 'secondary'],
            'submitted' => ['label' => 'Submitted', 'variant' => 'default'],
            'under_review' => ['label' => 'Under Review', 'variant' => 'secondary'],
            'admitted' => ['label' => 'Admitted', 'variant' => 'default'],
            'rejected' => ['label' => 'Rejected', 'variant' => 'destructive'],
            'enrolled' => ['label' => 'Enrolled', 'variant' => 'default'],
            'withdrawn' => ['label' => 'Withdrawn', 'variant' => 'outline'],
            'deferred' => ['label' => 'Deferred', 'variant' => 'secondary'],
            default => ['label' => ucfirst($this->application_status), 'variant' => 'secondary'],
        };
    }

    public function getVisaStatusBadgeAttribute(): array
    {
        return match($this->visa_status) {
            'not_started' => ['label' => 'Not Started', 'variant' => 'secondary'],
            'documents_collected' => ['label' => 'Documents Collected', 'variant' => 'default'],
            'application_submitted' => ['label' => 'Application Submitted', 'variant' => 'default'],
            'interview_scheduled' => ['label' => 'Interview Scheduled', 'variant' => 'secondary'],
            'interview_completed' => ['label' => 'Interview Completed', 'variant' => 'default'],
            'approved' => ['label' => 'Approved', 'variant' => 'default'],
            'rejected' => ['label' => 'Rejected', 'variant' => 'destructive'],
            'issued' => ['label' => 'Issued', 'variant' => 'default'],
            default => ['label' => ucfirst($this->visa_status), 'variant' => 'secondary'],
        };
    }

    public function getNetFeeAttribute(): float
    {
        return ($this->tuition_fee ?? 0) - ($this->scholarship_amount ?? 0);
    }

    public function isOverdue(): bool
    {
        return $this->submission_deadline && 
               Carbon::parse($this->submission_deadline)->isPast() && 
               $this->application_status === 'draft';
    }

    public function canSubmitVisa(): bool
    {
        return in_array($this->application_status, ['admitted', 'enrolled']) && 
               $this->visa_status === 'not_started';
    }
}
