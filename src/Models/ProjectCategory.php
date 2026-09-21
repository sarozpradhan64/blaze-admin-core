<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    use \Blaze\AdminCore\Traits\HasSortOrder, HasSeo;
    use HasFactory;

    protected $table = 'project_categories';

    protected $guarded = [];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
