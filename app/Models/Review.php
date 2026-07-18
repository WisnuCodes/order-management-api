<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $table = 'Reviews';
    protected $primaryKey = 'review_id';

    protected $fillable = [
        'product_id',
        'buyer_id',
        'rating',
        'comment',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id');
    }

    protected static function booted()
    {
        static::saved(function ($review) {
            $review->updateProductRating();
        });

        static::deleted(function ($review) {
            $review->updateProductRating();
        });
    }

    public function updateProductRating()
    {
        $product = $this->product;
        if ($product) {
            $average = $product->reviews()->avg('rating') ?? 0;
            $product->rating = round($average, 1);
            $product->save();
        }
    }
}
