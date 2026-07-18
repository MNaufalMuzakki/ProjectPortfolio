<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'role', 'bio', 'email', 'whatsapp', 
        'instagram', 'linkedin', 'github', 'address', 'avatar_path'
    ];
}
