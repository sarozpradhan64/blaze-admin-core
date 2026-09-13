<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory, \App\Traits\HasSortOrder;

    protected $table = 'gallery_items';
    protected $guarded = [];

    public function album() { return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id'); }

}


