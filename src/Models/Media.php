<?php

namespace Blaze\AdminCore\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $guarded = [];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
