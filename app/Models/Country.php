<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function universities(): HasMany
    {
        return $this->hasMany(University::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
