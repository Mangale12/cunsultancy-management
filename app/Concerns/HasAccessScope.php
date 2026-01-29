<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasAccessScope
{
    protected static function bootHasAccessScope(): void
    {
        static::addGlobalScope('access', function (Builder $builder) {
            $user = Auth::user();
            if (! $user) {
                return;
            }

            if (method_exists(static::class, 'applyAccessScope')) {
                static::applyAccessScope($builder, $user);
            }
        });
    }
}
