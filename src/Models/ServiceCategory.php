<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use \App\Traits\HasSeo, \App\Traits\HasSortOrder;
    use HasFactory;

    protected $table = 'service_categories';
    protected $guarded = [];

    public function services() { return $this->hasMany(Service::class); }

    public function seoMetadata() { return $this->morphOne(SeoMetadata::class, 'seoable'); }

}

