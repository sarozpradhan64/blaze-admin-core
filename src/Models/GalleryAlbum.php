<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Blaze\AdminCore\Traits\HasSeo;
use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasCache, HasFactory, HasSeo, HasSortOrder;

    protected $table = 'gallery_albums';

    protected $guarded = [];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(GalleryItem::class);
    }

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * Retrieve all active cached albums.
     */
    public static function cachedActive()
    {
        $data = static::rememberCache('active', function () {
            return static::where('status', true)->orderBy('sort_order')->get()->toArray();
        });

        if (! is_array($data)) {
            return collect();
        }

        return collect($data)->map(function ($item) {
            $instance = new static;
            $instance->setRawAttributes($item, true);
            $instance->exists = true;

            return $instance;
        });
    }

    public static function getCacheSuffixes(): array
    {
        return ['active'];
    }
}
