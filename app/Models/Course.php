<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'name',
        'level',
        'duration_months',
        'tuition_fee',
        'currency',
        'image_path',
    ];

    protected $casts = [
        'duration_months' => 'integer',
        'tuition_fee' => 'decimal:2',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }
}
