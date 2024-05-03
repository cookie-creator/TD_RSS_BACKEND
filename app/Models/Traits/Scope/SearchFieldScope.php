<?php

declare(strict_types=1);

namespace App\Models\Traits\Scope;

use App\Models\User;

/**
 * @method static whereUser(User $user) choose by user
 */
trait SearchFieldScope
{
    public function scopeSearchField($query, $value, string $field = 'name')
    {
        return $query->where($field, 'like', "%$value%");
    }
}
