<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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

    // Relationship with products through pivot table
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_gift_types');
    }

    // Scope for active gift types
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
        return asset('storage/gift-types/' . $this->image);
    }
}