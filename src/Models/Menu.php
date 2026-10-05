<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasCache, HasFactory, HasSortOrder;

    protected $fillable = [
        'title',
        'subtitle',
        'icon',
        'parent_id',
        'type',
        'reference_id',
        'url',
        'sort_order',
        'status',
        'is_editable',
        'is_deletable',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_editable' => 'boolean',
        'is_deletable' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    public function getUrlAttribute(): string
    {
        if ($this->type === 'custom_url') {
            return $this->attributes['url'] ?? '#';
        }

        if ($this->type === 'page' && $this->reference_id) {
            $page = Page::find($this->reference_id);

            return $page ? route('pages.show', $page->slug) : '#';
        }

        if ($this->type === 'service_category' && $this->reference_id) {
            // Adjust this when ServiceCategory logic is fully known
            return '/services/category/'.$this->reference_id;
        }

        return '#';
    }

    /**
     * Retrieve the cached navigation menu tree (top-level menus with active children).
     *
     * @return Collection<int, self>
     */
    public static function cachedTree(): Collection
    {
        $data = static::rememberCache('tree', function () {
            return static::whereNull('parent_id')
                ->where('status', true)
                ->orderBy('sort_order')
                ->with(['children' => function ($query) {
                    $query->where('status', true)->orderBy('sort_order');
                }])
                ->get()
                ->toArray();
        });

        if (! is_array($data) || empty($data)) {
            return new Collection;
        }

        $items = collect($data)->map(function ($menuData) {
            $childrenData = $menuData['children'] ?? [];
            unset($menuData['children']);

            $menu = new static;
            $menu->setRawAttributes($menuData, true);
            $menu->exists = true;

            $children = collect($childrenData)->map(function ($childData) {
                $child = new static;
                $child->setRawAttributes($childData, true);
                $child->exists = true;

                return $child;
            });

            $menu->setRelation('children', new Collection($children->all()));

            return $menu;
        });

        return new Collection($items->all());
    }

    public static function getCacheSuffixes(): array
    {
        return ['tree'];
    }
}
