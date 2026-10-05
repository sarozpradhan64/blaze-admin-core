<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasCache, HasFactory;

    protected $table = 'website_settings';

    protected $guarded = [];

    /**
     * Retrieve all website settings as key-value pairs from cache.
     *
     * @return array<string, mixed>
     */
    public static function cached(): array
    {
        $data = static::rememberCache('all', function () {
            return static::pluck('value', 'key')->toArray();
        });

        return is_array($data) ? $data : [];
    }

    /**
     * Get a specific setting value by key with optional fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::cached()[$key] ?? $default;
    }
}
