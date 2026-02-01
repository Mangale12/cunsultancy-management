<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'status',
        'comment',
    ];

    public function application()
    {
        return $this->belongsTo(StudentApplication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
