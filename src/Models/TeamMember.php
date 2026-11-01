<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\Filterable;
use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    use Filterable, HasFactory, HasSortOrder;

    protected $table = 'team_members';

    protected $guarded = [];

    /**
     * In-memory cache for route existence check.
     */
    protected static ?bool $hasShowRoute = null;

    protected static function booted(): void
    {
        static::saving(function (TeamMember $member) {
            if (empty($member->slug) && ! empty($member->name)) {
                $member->slug = static::generateUniqueSlug($member->name, $member->id);
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?int $id = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug ?: 'member';
        $count = 1;

        while (static::where('slug', $slug)->when($id, fn ($q) => $q->where('id', '!=', $id))->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    public static function findBySlugOrId(string|int $identifier): ?self
    {
        return static::where(function ($query) use ($identifier) {
            $query->where('slug', $identifier)
                ->orWhere('id', $identifier);
        })->first();
    }

    public function getRoleAttribute(): ?string
    {
        return $this->attributes['role'] ?? $this->attributes['designation'] ?? null;
    }

    public function setRoleAttribute($value): void
    {
        $this->attributes['role'] = $value;
        $this->attributes['designation'] = $value;
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status'] = (bool) $value;
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) ($this->attributes['status'] ?? true);
    }

    public function getUrlAttribute(): ?string
    {
        static::$hasShowRoute ??= Route::has('team.show');

        if (! $this->slug || ! static::$hasShowRoute) {
            return null;
        }

        return route('team.show', $this->slug);
    }
}
