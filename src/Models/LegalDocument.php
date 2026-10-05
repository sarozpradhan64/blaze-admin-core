<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Blaze\AdminCore\Traits\HasSeo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    use HasCache, HasFactory, HasSeo;

    protected $table = 'legal_documents';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * Retrieve all active legal documents from cache.
     *
     * @return Collection<int, self>
     */
    public static function cached(): Collection
    {
        $data = static::rememberCache('active', function () {
            return static::where('status', true)->latest()->get()->toArray();
        });

        if (! is_array($data) || empty($data)) {
            return new Collection;
        }

        return static::hydrate($data);
    }
}
