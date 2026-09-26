<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasFactory, HasSortOrder;

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
}
