<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'is_required',
        'has_expiry',
        'allowed_file_types',
        'max_file_size',
        'is_active',
        'sort_order',
        'allows_multiple_files',
        'max_files',
        'is_visa_required',
        'visa_document_type',
        'visa_requirements',
        'has_expiry_validation',
        'expiry_warning_days',
        'requires_verification',
        'requires_notarization',
        'requires_translation',
    ];

    protected $casts = [
        'allowed_file_types' => 'array',
        'is_required' => 'boolean',
        'has_expiry' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'max_file_size' => 'integer',
        'allows_multiple_files' => 'boolean',
        'max_files' => 'integer',
        'is_visa_required' => 'boolean',
        'has_expiry_validation' => 'boolean',
        'expiry_warning_days' => 'integer',
        'requires_verification' => 'boolean',
        'requires_notarization' => 'boolean',
        'requires_translation' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get category badge color
     */
    public function getCategoryBadgeAttribute(): array
    {
        return match($this->category) {
            'academic' => ['label' => 'Academic', 'variant' => 'default'],
            'financial' => ['label' => 'Financial', 'variant' => 'secondary'],
            'identity' => ['label' => 'Identity', 'variant' => 'outline'],
            'medical' => ['label' => 'Medical', 'variant' => 'secondary'],
            'visa' => ['label' => 'Visa', 'variant' => 'destructive'],
            'language' => ['label' => 'Language', 'variant' => 'default'],
            'experience' => ['label' => 'Experience', 'variant' => 'secondary'],
            'recommendation' => ['label' => 'Recommendation', 'variant' => 'outline'],
            'personal' => ['label' => 'Personal', 'variant' => 'default'],
            'travel' => ['label' => 'Travel', 'variant' => 'secondary'],
            'accommodation' => ['label' => 'Accommodation', 'variant' => 'outline'],
            'other' => ['label' => 'Other', 'variant' => 'secondary'],
            default => ['label' => ucfirst($this->category), 'variant' => 'secondary'],
        };
    }

    /**
     * Get category icon
     */
    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'academic' => '🎓',
            'financial' => '💰',
            'identity' => '🆔',
            'medical' => '🏥',
            'visa' => '🛂',
            'language' => '🗣️',
            'experience' => '💼',
            'recommendation' => '📝',
            'personal' => '👤',
            'travel' => '✈️',
            'accommodation' => '🏠',
            'other' => '📄',
            default => '📄',
        };
    }

    /**
     * Get processing requirements as array
     */
    public function getProcessingRequirementsAttribute(): array
    {
        $requirements = [];
        
        if ($this->requires_verification) {
            $requirements[] = 'Verification Required';
        }
        
        if ($this->requires_notarization) {
            $requirements[] = 'Notarization Required';
        }
        
        if ($this->requires_translation) {
            $requirements[] = 'Translation Required';
        }
        
        if ($this->is_visa_required) {
            $requirements[] = 'Visa Document';
        }
        
        return $requirements;
    }

    /**
     * Check if document is expiring soon
     */
    public function isExpiringSoon($document = null): bool
    {
        if (!$this->has_expiry_validation || !$document) {
            return false;
        }
        
        return $document->expiry_date && 
               $document->expiry_date->diffInDays(now()) <= $this->expiry_warning_days;
    }

    /**
     * Scope for visa documents
     */
    public function scopeVisaDocuments($query)
    {
        return $query->where('is_visa_required', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for documents requiring verification
     */
    public function scopeRequiringVerification($query)
    {
        return $query->where('requires_verification', true);
    }

    /**
     * Scope to get active document types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get required document types
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Check if file type is allowed
     */
    public function isFileTypeAllowed($fileType): bool
    {
        return in_array(strtolower($fileType), $this->allowed_file_types);
    }

    /**
     * Get max file size in MB
     */
    public function getMaxFileSizeMbAttribute(): float
    {
        return $this->max_file_size / 1024;
    }

    /**
     * Check if document type allows multiple files
     */
    public function allowsMultipleFiles(): bool
    {
        return $this->attributes['allows_multiple_files'] ?? false;
    }

    /**
     * Get maximum number of files allowed
     */
    public function getMaxFilesAttribute(): int
    {
        return $this->attributes['max_files'] ?? 1;
    }

    /**
     * Get total maximum file size in MB
     */
    public function getTotalMaxFileSizeMbAttribute(): float
    {
        $allowsMultiple = $this->attributes['allows_multiple_files'] ?? false;
        $maxFiles = $this->attributes['max_files'] ?? 1;
        
        return $allowsMultiple 
            ? ($maxFiles * $this->max_file_size / 1024)
            : $this->max_file_size / 1024;
    }
}
