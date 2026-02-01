<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Branch;
use App\Models\Agent;
use App\Models\Country;
use App\Models\State;
use App\Models\Course;
use App\Models\Commission;
use App\Models\StudentApplication;

class Student extends Model
{
    /**
     * Get the route key name for the model.
     *
     * @return string
     */
    /**
     * Get the route key name for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'country_id',
        'state_id',
        'branch_id',
        'agent_id',
        'course_id',
        'status',
        'image_path',
        'user_id',
        'application_status',
        'application_completed_at',
        'application_notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // protected $casts = [
    //     'date_of_birth' => 'date',
    //     'application_completed_at' => 'datetime',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];

    /**
     * Get the application status label
     *
     * @return string
     */
    public function getApplicationStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'in_review' => 'In Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
        ];

        return $statuses[$this->application_status] ?? 'Unknown';
    }

    protected $casts = [
        'date_of_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the student's full name
     */
    public function getNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the student's documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class, 'student_id', 'id');
    }

    /**
     * Get the student's verified documents
     */
    public function verifiedDocuments(): HasMany
    {
        return $this->hasMany(Document::class)->where('status', 'verified');
    }

    /**
     * Get the student's applications
     */
    public function applications(): HasMany
    {
        return $this->hasMany(StudentApplication::class);
    }

    /**
     * Get the student's active application
     */
    public function activeApplication(): HasOne
    {
        return $this->hasOne(StudentApplication::class)->active();
    }

    /**
     * Get the student's payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }

    /**
     * Get the student's pending payments
     */
    public function pendingPayments(): HasMany
    {
        return $this->hasMany(StudentPayment::class)->pending();
    }

    /**
     * Get the student's overdue payments
     */
    public function overduePayments(): HasMany
    {
        return $this->hasMany(StudentPayment::class)->overdue();
    }

    /**
     * Get the student's pending documents
     */
    public function pendingDocuments(): HasMany
    {
        return $this->hasMany(Document::class)->where('status', 'pending');
    }

    /**
     * Get the student's expired documents
     */
    public function expiredDocuments(): HasMany
    {
        return $this->hasMany(Document::class)->expired();
    }

    /**
     * Scope for students visible to the user
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        // Superadmin can see all
        if ($user->hasRole('superadmin')) {
            return $query;
        }

        // Branch admin can see students in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $query->where('branch_id', $user->employee->branch_id);
        }

        // Agent can see their direct students only
        if ($user->hasRole('agent') && $user->agent) {
            return $query->where('agent_id', $user->agent->id);
        }

        // Employee can see students in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return $query->where('branch_id', $user->employee->branch_id);
        }

        // Student can see themselves only
        if ($user->hasRole('student') && $user->student) {
            return $query->where('id', $user->student->id);
        }

        // No access for others
        return $query->whereRaw('1 = 0');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }
}
