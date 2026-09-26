<?php

namespace Blaze\AdminCore\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Controller
{
    protected function uniqueSlug(string $value, string $table, ?int $excludeId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $i = 2;

        while (
            DB::table($table)
                ->where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
