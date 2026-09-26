<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatistic extends Model
{
    use HasFactory, HasSortOrder;

    protected $table = 'project_statistics';

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
