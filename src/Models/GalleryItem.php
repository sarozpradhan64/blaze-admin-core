<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use \Blaze\AdminCore\Traits\HasSortOrder, HasFactory, HasTags;

    protected $table = 'gallery_items';

    protected $guarded = [];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }
}
