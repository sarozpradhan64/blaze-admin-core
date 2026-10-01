<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
        'cv',
        'cover_letter',
        'license',
        'other_documents',
        'status',
    ];

    protected $casts = [
        'other_documents' => 'array',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function getCvUrlAttribute(): ?string
    {
        return $this->cv ? Storage::disk('public')->url($this->cv) : null;
    }

    public function getLicenseUrlAttribute(): ?string
    {
        return $this->license ? Storage::disk('public')->url($this->license) : null;
    }
}
