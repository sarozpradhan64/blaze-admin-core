<?php

namespace Blaze\AdminCore\Traits;

use Blaze\AdminCore\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasTags
{
    protected static function bootHasTags(): void
    {
        static::deleted(function (Model $model): void {
            $tagIds = $model->tags()->pluck('tags.id')->all();

            $model->tags()->detach();
            Tag::refreshUsageCounts($tagIds);
        });
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->withTimestamps()
            ->orderBy('name');
    }

    public function syncTagsFromString(?string $tags): void
    {
        $existingTagIds = $this->tags()->pluck('tags.id')->all();

        $tagIds = $this->parseTags($tags)
            ->map(fn (string $name): Tag => Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            ))
            ->pluck('id')
            ->all();

        $this->tags()->sync($tagIds);
        Tag::refreshUsageCounts([...$existingTagIds, ...$tagIds]);
    }

    public function tagList(): string
    {
        $tags = $this->relationLoaded('tags') ? $this->tags : $this->tags()->get();

        return $tags->pluck('name')->implode(', ');
    }

    protected function parseTags(?string $tags): Collection
    {
        return str($tags ?? '')
            ->explode(',')
            ->map(fn (string $tag): string => trim($tag))
            ->filter(fn (string $tag): bool => $tag !== '' && Str::slug($tag) !== '')
            ->unique(fn (string $tag): string => Str::slug($tag))
            ->values();
    }
}
