<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\Filterable;
use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, HasSortOrder, Filterable;

    protected $table = 'testimonials';

    protected $guarded = [];
}
