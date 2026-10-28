<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\Filterable;
use Blaze\AdminCore\Traits\HandlesMedia;
use Blaze\AdminCore\Traits\HasFaqs;
use Blaze\AdminCore\Traits\HasSeo;
use Blaze\AdminCore\Traits\HasSortOrder;
use Blaze\AdminCore\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Service extends Model
{
    use Filterable, HandlesMedia, HasFaqs, HasSeo, HasSortOrder, HasTags;
    use HasFactory;

    protected array $richTextFields = ['description'];

    protected $table = 'services';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function features()
    {
        return $this->hasMany(ServiceFeature::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function galleryAlbum()
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function testimonials()
    {
        return $this->belongsToMany(Testimonial::class, 'service_testimonial');
    }

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * In-memory cache for category slugs keyed by category ID to prevent N+1 queries.
     *
     * @var array<int, string>|null
     */
    protected static ?array $categorySlugCache = null;

    /**
     * In-memory cache for route existence check.
     */
    protected static ?bool $hasShowRoute = null;

    public function getUrlAttribute(): ?string
    {
        static::$hasShowRoute ??= Route::has('services.show');

        if (! $this->slug || ! static::$hasShowRoute) {
            return null;
        }

        if ($this->relationLoaded('category') && $this->category) {
            $categorySlug = $this->category->slug;
        } elseif ($this->service_category_id) {
            static::$categorySlugCache ??= ServiceCategory::pluck('slug', 'id')->all();
            $categorySlug = static::$categorySlugCache[$this->service_category_id] ?? 'trekking';
        } else {
            $categorySlug = 'trekking';
        }

        return route('services.show', [
            'categorySlug' => $categorySlug,
            'serviceSlug' => $this->slug,
        ]);
    }

    public static function flushCategorySlugCache(): void
    {
        static::$categorySlugCache = null;
        static::$hasShowRoute = null;
    }
}
