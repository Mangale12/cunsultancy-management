<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Branch;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'position',
        'department',
        'salary',
        'hire_date',
        'is_active',
        'job_title',
        'joined_at',
        'image_path',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
        'hire_date' => 'date',
        'joined_at' => 'datetime',
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

    /**
     * Get the employee's full name
     */
    public function getNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Scope for employees visible to the user
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        // Superadmin can see all
        if ($user->hasRole('superadmin')) {
            return $query;
        }

        // Branch admin can see employees in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $query->where('branch_id', $user->employee->branch_id);
        }

        // Employee can see employees in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return $query->where('branch_id', $user->employee->branch_id);
        }

        // No access for others
        return $query->whereRaw('1 = 0');
    }
}
