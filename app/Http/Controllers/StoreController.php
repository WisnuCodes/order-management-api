<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class StoreController extends Controller
{
    use ApiResponse;

    public function show(string $username)
    {
        // Cari user (seller) berdasarkan username
        $seller = User::where('username', $username)->where('role', 'seller')->first();

        // Jika seller tidak ditemukan, kita kembalikan 404
        if (!$seller) {
            return $this->errorResponse('Toko tidak ditemukan', 404);
        }

        // Ambil produk yang berstatus active milik seller ini
        $products = Product::with(['category'])
            ->where('seller_id', $seller->user_id)
            ->where('status', 'active')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->get();

        $formattedProducts = $products->map(function ($product) use ($seller) {
            $reviewsCount = $product->reviews_count ?? 0;
            $rating = $reviewsCount > 0 ? (float) ($product->reviews_avg_rating ?? 0) : 0;

            return [
                'id' => $product->product_id,
                'title' => $product->title,
                'description' => $product->description,
                'price' => (float) $product->price,
                'rating' => $rating,
                'reviews_count' => $reviewsCount,
                'thumbnail' => $product->thumbnail,
                'download_count' => $product->download_count,
                'category' => [
                    'id' => $product->category->category_id ?? null,
                    'name' => $product->category->name ?? null,
                ],
                'seller' => [
                    'id' => $seller->user_id,
                    'name' => $seller->name,
                    'username' => $seller->username
                ],
            ];
        });

        $followersCount = \App\Models\Follow::where('seller_id', $seller->user_id)->count();

        return $this->successResponse([
            'seller' => [
                'id' => $seller->user_id,
                'name' => $seller->name,
                'username' => $seller->username,
                'joined_at' => $seller->created_at ? $seller->created_at->format('Y-m-d') : '-',
                'followers_count' => $followersCount
            ],
            'products' => $formattedProducts,
            'total_products' => $formattedProducts->count()
        ], 'Data profil toko berhasil diambil');
    }
}
