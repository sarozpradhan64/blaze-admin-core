<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasCache, HasFactory;

    protected $table = 'social_links';

    protected $guarded = [];

    /**
     * Retrieve all active social links ordered by sort order from cache.
     *
     * @return Collection<int, self>
     */
    public static function cached(): Collection
    {
        $data = static::rememberCache('active', function () {
            return static::where('status', true)->orderBy('sort_order')->get()->toArray();
        });

        if (! is_array($data) || empty($data)) {
            return new Collection;
        }

        return static::hydrate($data);
    }
}
