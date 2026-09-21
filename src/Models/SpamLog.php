<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpamLog extends Model
{
    use HasFactory;

    protected $table = 'spam_logs';

    protected $guarded = [];
}
