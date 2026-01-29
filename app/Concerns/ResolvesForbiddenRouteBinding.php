<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;

trait ResolvesForbiddenRouteBinding
{
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $field = $field ?? $this->getRouteKeyName();

        // Check if the branch exists with global scopes first
        $model = static::where($field, $value)->first();
        if ($model) {
            return $model;
        }

        // If not found with global scopes, check without global scopes
        $existsUnscoped = static::withoutGlobalScopes()
            ->where($field, $value)
            ->exists();

        if ($existsUnscoped) {
            abort(403);
        }

        return null;
    }
}
