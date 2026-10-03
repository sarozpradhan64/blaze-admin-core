<?php

namespace Blaze\AdminCore\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeSearch(Builder $query, ?string $term, array $columns = ['title'])
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', "%{$term}%");
            }
        });
    }

    public function scopeSort(Builder $query, ?string $sortBy, ?string $sortDirection = 'asc', string $defaultSort = 'sort_order', string $defaultDirection = 'asc')
    {
        $direction = strtolower($sortDirection) === 'desc' ? 'desc' : 'asc';

        if (! empty($sortBy)) {
            return $query->orderBy($sortBy, $direction);
        }

        if (! empty($defaultSort)) {
            return $query->orderBy($defaultSort, $defaultDirection);
        }

        return $query;
    }

    public function scopeFilterStatus(Builder $query, ?string $status)
    {
        if ($status !== null && $status !== '' && $status !== 'all') {
            $statusBool = in_array($status, ['1', 'true', 'active', 1, true], true);

            return $query->where('status', $statusBool);
        }

        return $query;
    }
}
