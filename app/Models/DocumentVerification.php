<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'verified_by',
        'status',
        'notes',
        'rejection_reason',
        'verification_checklist',
        'verified_at',
    ];

    protected $casts = [
        'verification_checklist' => 'array',
        'verified_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending' => ['label' => 'Pending', 'variant' => 'secondary'],
            'approved' => ['label' => 'Approved', 'variant' => 'default'],
            'rejected' => ['label' => 'Rejected', 'variant' => 'destructive'],
            'needs_revision' => ['label' => 'Needs Revision', 'variant' => 'outline'],
            default => ['label' => ucfirst($this->status), 'variant' => 'secondary'],
        };
    }

    /**
     * Scope to get verifications by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get latest verifications
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('verified_at', 'desc');
    }
}
