<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Branch;
use App\Models\Agent;
use App\Models\Student;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'agent_id',
        'student_id',
        'amount',
        'percentage',
        'type',
        'status',
        'description',
        'commission_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'commission_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope for commissions visible to the user
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        // Superadmin can see all
        if ($user->hasRole('superadmin')) {
            return $query;
        }

        // Branch admin can see commissions in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $query->where('branch_id', $user->employee->branch_id);
        }

        // Agent can see their commissions only
        if ($user->hasRole('agent') && $user->agent) {
            return $query->where('agent_id', $user->agent->id);
        }

        // Employee can see commissions in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return $query->where('branch_id', $user->employee->branch_id);
        }

        // No access for others
        return $query->whereRaw('1 = 0');
    }
}
