<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $guarded = [];

    protected $casts = [
        'usage_count' => 'integer',
    ];

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'taggable');
    }

    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'taggable');
    }

    public function blogs(): MorphToMany
    {
        return $this->morphedByMany(Blog::class, 'taggable');
    }

    public function galleryItems(): MorphToMany
    {
        return $this->morphedByMany(GalleryItem::class, 'taggable');
    }

    public static function refreshUsageCounts(array $tagIds = []): void
    {
        $tagIds = array_values(array_unique(array_filter($tagIds)));

        if ($tagIds === []) {
            return;
        }

        static::whereKey($tagIds)->update(['usage_count' => 0]);

        DB::table('taggables')
            ->select('tag_id', DB::raw('count(*) as aggregate'))
            ->whereIn('tag_id', $tagIds)
            ->groupBy('tag_id')
            ->orderBy('tag_id')
            ->each(function (object $taggable): void {
                static::whereKey($taggable->tag_id)->update(['usage_count' => $taggable->aggregate]);
            });
    }
}
