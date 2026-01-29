<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Branch;
use App\Models\Student;
use App\Models\Commission;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'user_id',
        'parent_agent_id',
        'name',
        'code',
        'email',
        'phone',
        'image_path',
    ];

    protected $casts = [
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

    public function parentAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'parent_agent_id');
    }

    public function childAgents(): HasMany
    {
        return $this->hasMany(Agent::class, 'parent_agent_id');
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
     * Scope for agents visible to the user
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        // Superadmin can see all
        if ($user->hasRole('superadmin')) {
            return $query;
        }

        // Branch admin can see agents in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $query->where('branch_id', $user->employee->branch_id);
        }

        // Agent can see themselves and their direct child agents only
        if ($user->hasRole('agent') && $user->agent) {
            return $query->where(function ($q) use ($user) {
                $q->where('id', $user->agent->id)
                  ->orWhere('parent_agent_id', $user->agent->id);
            });
        }

        // No access for others
        return $query->whereRaw('1 = 0');
    }
}
