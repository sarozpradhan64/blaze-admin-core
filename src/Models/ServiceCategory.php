<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Blaze\AdminCore\Traits\HasSeo;
use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class ServiceCategory extends Model
{
    use HasCache, HasFactory;
    use HasSeo, HasSortOrder;

    protected $table = 'service_categories';

    protected $guarded = [];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * Get all category IDs for this category and all its nested descendants.
     *
     * @return array<int>
     */
    public function getAllCategoryIds(): array
    {
        $ids = [(int) $this->id];
        $toCheck = [$this->id];

        while (! empty($toCheck)) {
            $next = self::whereIn('parent_id', $toCheck)->pluck('id')->all();
            if (empty($next)) {
                break;
            }
            $ids = array_merge($ids, $next);
            $toCheck = $next;
        }

        return array_values(array_unique($ids));
    }

    /**
     * Get all ancestor categories from top-level down to the immediate parent.
     *
     * @return Collection<int, self>
     */
    public function ancestors(): Collection
    {
        $ancestors = collect();
        $current = $this->parent;

        while ($current) {
            $ancestors->prepend($current);
            $current = $current->parent;
        }

        return $ancestors;
    }
}
