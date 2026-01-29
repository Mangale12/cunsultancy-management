<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DocumentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'file_hash',
        'description',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get the document that owns the file.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the file URL.
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFileSizeMbAttribute(): float
    {
        return round($this->file_size / 1024 / 1024, 2);
    }

    /**
     * Get the file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Get the file icon based on file type.
     */
    public function getFileIconAttribute(): string
    {
        $extension = strtolower($this->extension);
        
        return match($extension) {
            'pdf' => '📄',
            'doc', 'docx' => '📝',
            'xls', 'xlsx' => '📊',
            'ppt', 'pptx' => '📽',
            'jpg', 'jpeg', 'png', 'gif', 'bmp' => '🖼️',
            'zip', 'rar', '7z' => '📦',
            'txt', 'rtf' => '📄',
            default => '📎',
        };
    }

    /**
     * Check if the file is an image.
     */
    public function isImage(): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        return in_array(strtolower($this->extension), $imageExtensions);
    }

    /**
     * Check if the file is a PDF.
     */
    public function isPdf(): bool
    {
        return strtolower($this->extension) === 'pdf';
    }

    /**
     * Check if the file is a document.
     */
    public function isDocument(): bool
    {
        $documentExtensions = ['doc', 'docx', 'pdf', 'txt', 'rtf', 'odt', 'ods', 'odp'];
        return in_array(strtolower($this->extension), $documentExtensions);
    }

    /**
     * Check if the file is an archive.
     */
    public function isArchive(): bool
    {
        $archiveExtensions = ['zip', 'rar', '7z', 'tar', 'gz'];
        return in_array(strtolower($this->extension), $archiveExtensions);
    }

    /**
     * Scope to get primary files.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to get secondary files.
     */
    public function scopeSecondary($query)
    {
        return $query->where('is_primary', false);
    }

    /**
     * Scope to get files ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
