<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use \Blaze\AdminCore\Traits\HasSortOrder, HasFactory;

    protected $table = 'gallery_items';

    protected $guarded = [];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }
}
