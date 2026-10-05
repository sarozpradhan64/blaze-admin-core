<?php

namespace Blaze\AdminCore\Models;

use App\Models\User;
use Blaze\AdminCore\Traits\Filterable;
use Blaze\AdminCore\Traits\HandlesMedia;
use Blaze\AdminCore\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Filterable;
    use HandlesMedia;
    use HasFactory;
    use HasSeo;

    protected array $richTextFields = ['content'];

    protected $table = 'pages';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('type', function ($builder) {
            $builder->where('type', 'page');
        });

        static::creating(function ($page) {
            $page->type = 'page';
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
