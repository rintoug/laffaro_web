<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description'
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    protected $appends = ['image_url'];

    // Relationship with article products (one article has many products)
    public function products()
    {
        return $this->hasMany(GiftArticleProduct::class);
    }

    // Scope for active articles
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
        return asset('storage/articles/' . $this->image);
    }
}