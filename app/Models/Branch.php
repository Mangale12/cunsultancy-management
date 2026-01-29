<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\Employee;
use App\Models\Agent;
use App\Models\Student;
use App\Models\Commission;
use App\Concerns\HasAccessScopes;

class Branch extends Model
{
    use HasFactory;
    use HasAccessScopes;

    protected $fillable = [
        'country_id',
        'state_id',
        'name',
        'code',
        'address',
        'phone',
        'email',
        'manager_name',
        'is_active',
        'image_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Apply branch admin specific scope for Branch model
     */
    protected function applyBranchAdminSpecificScope(Builder $query, User $user): Builder
    {
        // Branch admin can only see their own branch
        if ($user->employee && $user->employee->branch_id) {
            return $query->where('id', $user->employee->branch_id);
        }
        
        return $query;
    }

    /**
     * Apply agent specific scope for Branch model
     */
    protected function applyAgentSpecificScope(Builder $query, User $user): Builder
    {
        // Agent can only see their own branch
        if ($user->agent && $user->agent->branch_id) {
            return $query->where('id', $user->agent->branch_id);
        }
        
        return $query;
    }

    /**
     * Apply employee specific scope for Branch model
     */
    protected function applyEmployeeSpecificScope(Builder $query, User $user): Builder
    {
        // Employee can only see their own branch
        if ($user->employee && $user->employee->branch_id) {
            return $query->where('id', $user->employee->branch_id);
        }
        
        return $query;
    }
}
