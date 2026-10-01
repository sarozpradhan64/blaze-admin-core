<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_postings';

    protected $fillable = [
        'job_category_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'requirements',
        'positions',
        'location',
        'salary',
        'deadline',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'deadline' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
