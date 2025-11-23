<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'short_description',
        'description',
        'aff_link',
        'price',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'integer',
    ];

    protected $appends = ['image_url', 'image_medium_url'];

    // Relationship with categories through pivot table
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    // Relationship with gift types through pivot table
    public function giftTypes()
    {
        return $this->belongsToMany(GiftType::class, 'product_gift_types');
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Accessor for original image URL
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }
        return asset('storage/products/original/' . $this->image);
    }

    // Accessor for medium image URL
    public function getImageMediumUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }
        return asset('storage/products/medium/' . $this->image);
    }
}