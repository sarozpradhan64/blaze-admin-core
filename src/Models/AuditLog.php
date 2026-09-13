<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    protected $guarded = [];

    public function user() { return $this->belongsTo(User::class); }

    public function auditable() { return $this->morphTo(); }

}

