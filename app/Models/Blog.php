<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'status',
        'short_description',
        'meta_title',
        'meta_keywords',
        'meta_description'
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    protected $appends = [
        'image_url'
    ];
    
    protected $visible = [
        'id',
        'title',
        'slug',
        'image',
        'image_url',
        'short_description',
        'description',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'created_at',
        'updated_at'
    ];

    // Scope for active blogs
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }
        return asset('storage/blog/' . $this->image);
    }
}