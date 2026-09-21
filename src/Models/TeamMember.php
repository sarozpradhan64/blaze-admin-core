<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use \Blaze\AdminCore\Traits\HasSortOrder, HasFactory;

    protected $table = 'team_members';

    protected $guarded = [];
}
