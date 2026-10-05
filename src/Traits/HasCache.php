<?php

namespace Blaze\AdminCore\Traits;

use Illuminate\Support\Facades\Cache;

trait HasCache
{
    /**
     * In-memory request-level cache to prevent repeated cache store hits during the same request.
     *
     * @var array<string, mixed>
     */
    protected static array $requestMemo = [];

    /**
     * Default cache TTL in seconds (24 hours).
     */
    protected static int $defaultCacheTtl = 86400;

    /**
     * Boot the trait and register Eloquent event listeners to auto-invalidate cache.
     */
    public static function bootHasCache(): void
    {
        static::saved(function ($model) {
            static::flushCache();
        });

        static::deleted(function ($model) {
            static::flushCache();
        });
    }

    /**
     * Derive a unique cache key for this model class and optional suffix.
     */
    public static function getCacheKey(?string $suffix = null): string
    {
        $base = 'cache_'.strtolower(class_basename(static::class));

        return $suffix ? "{$base}_{$suffix}" : $base;
    }

    /**
     * Cache a callback's result using both in-memory memoization and Laravel Cache.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    protected static function rememberCache(?string $suffix, callable $callback, ?int $ttl = null): mixed
    {
        $key = static::getCacheKey($suffix);

        if (array_key_exists($key, static::$requestMemo)) {
            return static::$requestMemo[$key];
        }

        $ttlSeconds = $ttl ?? static::$defaultCacheTtl;

        $result = rescue(
            fn () => Cache::remember($key, now()->addSeconds($ttlSeconds), $callback),
            $callback,
            false
        );

        return static::$requestMemo[$key] = $result;
    }

    /**
     * Flush all cached entries and in-memory memoization for this model.
     */
    public static function flushCache(?string $suffix = null): void
    {
        if ($suffix !== null) {
            $key = static::getCacheKey($suffix);
            unset(static::$requestMemo[$key]);
            Cache::forget($key);

            return;
        }

        // Forget the base key
        $baseKey = static::getCacheKey();
        unset(static::$requestMemo[$baseKey]);
        Cache::forget($baseKey);

        // Forget declared suffixes
        foreach (static::getCacheSuffixes() as $s) {
            $key = static::getCacheKey($s);
            unset(static::$requestMemo[$key]);
            Cache::forget($key);
        }

        // Also clean up any suffixed memo keys for this class
        $prefix = $baseKey.'_';
        foreach (array_keys(static::$requestMemo) as $memoKey) {
            if (str_starts_with($memoKey, $prefix)) {
                unset(static::$requestMemo[$memoKey]);
                Cache::forget($memoKey);
            }
        }
    }

    /**
     * Get the list of cache suffixes used by this model.
     * Override this in the model if you use suffixes.
     *
     * @return array<int, string>
     */
    public static function getCacheSuffixes(): array
    {
        return [];
    }
}
