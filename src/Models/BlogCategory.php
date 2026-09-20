<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory, \Blaze\AdminCore\Traits\HasSortOrder;

    protected $table = 'blog_categories';

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }
}
