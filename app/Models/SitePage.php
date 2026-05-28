<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
