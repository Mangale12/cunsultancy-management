<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class StudentDocument extends Model
{
    protected $fillable = [
        'student_id',
        'document_type_id',
        'title',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'notes',
        'is_verified',
        'verified_by',
        'verified_at',
    ];

    protected $appends = [
        'formatted_file_size',
        'document_type_label',
        'file_icon',
        'download_url',
        'preview_url',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'file_size' => 'integer',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     */
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         if (empty($model->document_type_id) && $model->documentType) {
    //             $model->document_type_id = $model->documentType->key;
    //         }
    //     });
    // }

    /**
     * Get the student that owns the document.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the document type associated with the document.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Get the user who verified the document.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the file size in a human-readable format.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the document type label.
     */
    public function getDocumentTypeLabelAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->document_type));
    }
}
