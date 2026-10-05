<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInformation extends Model
{
    use HasCache, HasFactory;

    protected $table = 'contact_information';

    protected $guarded = [];

    /**
     * Retrieve the cached company contact information.
     */
    public static function cached(): ?self
    {
        $data = static::rememberCache('first', function () {
            return static::first()?->toArray();
        });

        if (! is_array($data)) {
            return null;
        }

        $instance = new static;
        $instance->setRawAttributes($data, true);
        $instance->exists = true;

        return $instance;
    }
}
