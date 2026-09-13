<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory, \App\Traits\HasSortOrder;

    protected $table = 'team_members';
    protected $guarded = [];

}

