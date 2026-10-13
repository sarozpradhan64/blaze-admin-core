<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\Filterable;
use Blaze\AdminCore\Traits\HasCache;
use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use Filterable, HasCache, HasFactory, HasSortOrder;

    protected $table = 'payment_methods';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Retrieve all active payment methods ordered by sort order from cache.
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

    public static function getCacheSuffixes(): array
    {
        return ['active'];
    }
}
