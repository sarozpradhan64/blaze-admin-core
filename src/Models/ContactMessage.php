<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory, Filterable;

    protected $table = 'contact_messages';

    protected $guarded = [];
}
