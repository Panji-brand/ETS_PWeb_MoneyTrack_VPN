<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToAuthenticatedUser
{
    protected static function bootBelongsToAuthenticatedUser(): void
    {
        static::addGlobalScope('owned_by_user', function (Builder $query) {
            if (Auth::check()) {
                $query->where($query->getModel()->qualifyColumn('user_id'), Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }
}
