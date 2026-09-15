<?php

namespace Blaze\AdminCore\Traits;

use Blaze\AdminCore\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    /**
     * Get the model's SEO metadata.
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * Get the SEO title (fallback to model title/name if metadata not set).
     */
    public function getSeoTitleAttribute()
    {
        return $this->seo?->meta_title ?? $this->title ?? $this->name ?? config('app.name');
    }

    /**
     * Get the SEO description (fallback to model excerpt/short_description if metadata not set).
     */
    public function getSeoDescriptionAttribute()
    {
        return $this->seo?->meta_description ?? $this->excerpt ?? $this->short_description ?? '';
    }
}


