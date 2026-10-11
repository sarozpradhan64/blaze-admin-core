<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasCache, HasFactory, HasSortOrder;

    protected $table = 'homepage_sections';

    protected $guarded = [];

    protected $casts = [
        'settings' => 'array',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function cachedOrdered()
    {
        return static::rememberCache('ordered', function () {
            return static::orderBy('sort_order')->get();
        });
    }

    public static function cachedActive()
    {
        return static::rememberCache('active', function () {
            return static::where('status', true)->orderBy('sort_order')->get();
        });
    }

    public static function getCacheSuffixes(): array
    {
        return ['ordered', 'active'];
    }
}
