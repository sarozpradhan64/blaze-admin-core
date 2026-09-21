<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use \Blaze\AdminCore\Traits\HasSortOrder, HasFactory;
    use HasSeo;

    protected $table = 'projects';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function videos()
    {
        return $this->hasMany(ProjectVideo::class);
    }

    public function statistics()
    {
        return $this->hasMany(ProjectStatistic::class);
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
