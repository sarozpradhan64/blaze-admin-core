<?php

namespace Blaze\AdminCore\Traits;

trait HasSortOrder
{
    public static function bootHasSortOrder(): void
    {
        static::creating(function ($model) {
            if (empty($model->sort_order)) {
                $model->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });
    }

    public static function applyReorder(array $ids): void
    {
        foreach ($ids as $position => $id) {
            static::where('id', $id)->update(['sort_order' => $position + 1]);
        }
    }
}
