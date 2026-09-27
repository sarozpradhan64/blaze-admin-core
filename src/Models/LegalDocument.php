<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    use HasFactory;
    use HasSeo;

    protected $table = 'legal_documents';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
