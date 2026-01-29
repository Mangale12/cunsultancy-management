<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'document_type_id',
        'title',
        'description',
        'status',
        'rejection_reason',
        'expiry_date',
        'is_required',
        'is_public',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_required' => 'boolean',
        'is_public' => 'boolean',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the student that owns the document.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the document type for this document.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Get the user who verified this document.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the verification records for this document.
     */
    public function verifications(): HasMany
    {
        return $this->hasMany(DocumentVerification::class);
    }

    /**
     * Get the files associated with this document.
     */
    public function files(): HasMany
    {
        return $this->hasMany(DocumentFile::class)->ordered();
    }

    /**
     * Get the primary file for this document.
     */
    public function primaryFile(): HasOne
    {
        return $this->hasOne(DocumentFile::class)->primary();
    }

    /**
     * Get the secondary files for this document.
     */
    public function secondaryFiles(): HasMany
    {
        return $this->hasMany(DocumentFile::class)->secondary()->ordered();
    }

    /**
     * Get the total size of all files in this document.
     */
    public function getTotalFileSizeAttribute(): int
    {
        return $this->files->sum('file_size');
    }

    /**
     * Get the total size in MB.
     */
    public function getTotalFileSizeMbAttribute(): float
    {
        return round($this->total_file_size / 1024 / 1024, 2);
    }

    /**
     * Get the count of files in this document.
     */
    public function getFileCountAttribute(): int
    {
        return $this->files->count();
    }

    /**
     * Check if the document has multiple files.
     */
    public function hasMultipleFiles(): bool
    {
        return $this->file_count > 1;
    }

    /**
     * Get the primary file name.
     */
    public function getPrimaryFileNameAttribute(): string
    {
        return $this->primaryFile?->file_name ?? 'No file';
    }

    /**
     * Get the primary file type.
     */
    public function getPrimaryFileTypeAttribute(): string
    {
        return $this->primaryFile?->file_type ?? 'unknown';
    }

    /**
     * Get the primary file size.
     */
    public function getPrimaryFileSizeAttribute(): int
    {
        return $this->primaryFile?->file_size ?? 0;
    }

    /**
     * Get the primary file URL.
     */
    public function getPrimaryFileUrlAttribute(): string
    {
        return $this->primaryFile ? $this->primaryFile->url : '';
    }

    /**
     * Get the primary file icon.
     */
    public function getPrimaryFileIconAttribute(): string
    {
        return $this->primaryFile ? $this->primaryFile->file_icon : '📎';
    }

    /**
     * Scope to get documents by status.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending documents.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get verified documents.
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope to get rejected documents.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to get expired documents.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', 'expired')
            ->orWhere('expiry_date', '<', now());
    }

    /**
     * Scope to get documents expiring soon.
     */
    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays($days));
    }

    /**
     * Scope to get required documents.
     */
    public function scopeRequired(Builder $query): Builder
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope to get public documents.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Check if the document is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->expiry_date && Carbon::parse($this->expiry_date)->isPast());
    }

    /**
     * Check if the document is expiring soon.
     */
    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiry_date && 
               Carbon::parse($this->expiry_date)->isBetween(now(), now()->addDays($days));
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute(): array
    {
        if ($this->isExpired()) {
            return ['label' => 'Expired', 'variant' => 'destructive'];
        }

        return match($this->status) {
            'pending' => ['label' => 'Pending', 'variant' => 'secondary'],
            'verified' => ['label' => 'Verified', 'variant' => 'default'],
            'rejected' => ['label' => 'Rejected', 'variant' => 'destructive'],
            'needs_revision' => ['label' => 'Needs Revision', 'variant' => 'outline'],
            default => ['label' => $this->status, 'variant' => 'secondary'],
        };
    }

    /**
     * Delete all associated files when document is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            // Delete all files from storage
            foreach ($document->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }
        });
    }
}
