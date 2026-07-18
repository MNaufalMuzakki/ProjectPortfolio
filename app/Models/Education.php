<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Education extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'title', 'subtitle', 'description', 
        'metrics', 'certificate_link'
    ];

    protected $casts = [
        'metrics' => 'array',
    ];
}
