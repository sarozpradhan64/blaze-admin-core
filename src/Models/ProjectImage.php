<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory, \Blaze\AdminCore\Traits\HasSortOrder;

    protected $table = 'project_images';
    protected $guarded = [];

    public function project() { return $this->belongsTo(Project::class); }

}


