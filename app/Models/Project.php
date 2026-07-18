<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'category', 'description', 
        'image_path', 'url', 'tech_stack'
    ];

    protected $casts = [
        'tech_stack' => 'array',
    ];
}
