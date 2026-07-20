<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'User';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'balance',
        'otp_code',
        'otp_expires_at',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id', 'user_id');
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id', 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'buyer_id', 'user_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'user_id');
    }

    /**
     * Seller: orang-orang yang mem-follow saya
     */
    public function followers(): HasMany
    {
        return $this->hasMany(Follow::class, 'seller_id', 'user_id');
    }

    /**
     * Buyer: seller-seller yang saya follow
     */
    public function following(): HasMany
    {
        return $this->hasMany(Follow::class, 'buyer_id', 'user_id');
    }
}