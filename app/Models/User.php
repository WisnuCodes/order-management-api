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
        'email',
        'password',
        'role',
        'balance',
    ];

    protected $hidden = [
        'password',
    ];

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
}