<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftArticleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_article_id',
        'title',
        'description',
        'image',
        'price',
        'aff_link',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'integer',
    ];

    protected $appends = ['image_url'];

    // Relationship to parent article
    public function giftArticle()
    {
        return $this->belongsTo(GiftArticle::class);
    }

    // Scope for active products
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
