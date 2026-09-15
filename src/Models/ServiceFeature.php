<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFeature extends Model
{
    use HasFactory, \Blaze\AdminCore\Traits\HasSortOrder;

    protected $table = 'service_features';
    protected $guarded = [];

    public function service() { return $this->belongsTo(Service::class); }

}


