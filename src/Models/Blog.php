<?php

namespace Blaze\AdminCore\Models;

use App\Models\User;
use Blaze\AdminCore\Traits\HasSeo;
use Blaze\AdminCore\Traits\HasSortOrder;
use Blaze\AdminCore\Traits\HasTags;
use Blaze\AdminCore\Traits\HasFaqs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    use HasSeo;
    use HasSortOrder;
    use HasTags;
    use HasFaqs;

    protected $table = 'pages';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
