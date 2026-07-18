<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', $request->user()->user_id)
            ->get();
            
        return response()->json($wishlists);
    }

    public function toggle(Request $request, $productId)
    {
        $userId = $request->user()->user_id;

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['message' => 'Produk dihapus dari wishlist', 'is_wishlisted' => false]);
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            return response()->json(['message' => 'Produk ditambahkan ke wishlist', 'is_wishlisted' => true]);
        }
    }
}
