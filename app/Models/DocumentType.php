<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends Model
{
    use SoftDeletes;
    protected $table = 'document_types';
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'is_required',
        'sort_order',
        'allowed_extensions',
        'max_file_size'
    ];
    
  
}
