<?php

namespace Blaze\AdminCore\Models;

use App\Models\User;
use Blaze\AdminCore\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    use HasSeo;

    protected $table = 'pages';

    protected $guarded = [];

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
