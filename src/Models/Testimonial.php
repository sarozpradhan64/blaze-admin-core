<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use \Blaze\AdminCore\Traits\HasSortOrder, HasFactory;

    protected $table = 'testimonials';

    protected $guarded = [];
}
