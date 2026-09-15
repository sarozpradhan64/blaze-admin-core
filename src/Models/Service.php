<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use \Blaze\AdminCore\Traits\HasSeo, \Blaze\AdminCore\Traits\HasSortOrder;
    use HasFactory;

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

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}


