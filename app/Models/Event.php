<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_slug',
        'latitude',
        'longitude',
        'source_url',
        'image_url',
        'source_name',
        'published_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_slug', 'slug');
    }
}
