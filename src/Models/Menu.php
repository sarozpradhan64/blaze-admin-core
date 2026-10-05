<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Blaze\AdminCore\Traits\HasSortOrder;

class Menu extends Model
{
    use HasFactory, HasSortOrder;

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

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
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
            return '/services/category/' . $this->reference_id;
        }

        return '#';
    }
}
