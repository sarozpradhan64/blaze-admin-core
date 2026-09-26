<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectVideo extends Model
{
    use HasFactory, HasSortOrder;

    protected $table = 'project_videos';

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
