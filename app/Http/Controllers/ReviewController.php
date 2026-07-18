<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function latest()
    {
        // Fetch 6 latest reviews across all products, including buyer and product info
        $reviews = Review::with(['buyer:user_id,name', 'product:product_id,title'])
            ->latest()
            ->take(6)
            ->get();

        return response()->json([
            'message' => 'Latest reviews retrieved successfully',
            'data' => $reviews
        ], 200);
    }

    public function index($product_id)
    {
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $reviews = Review::with('buyer:user_id,name')->where('product_id', $product_id)->latest()->get();

        return response()->json([
            'message' => 'Reviews retrieved successfully',
            'data' => $reviews
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:Product,product_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_id = Auth::id();
        $product_id = $request->product_id;

        // Check if user has bought this product and payment is success
        $hasPurchased = Order::where('buyer_id', $user_id)
            ->where('product_id', $product_id)
            ->where('payment_status', 'success')
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'message' => 'You must purchase this product successfully before reviewing it.'
            ], 403);
        }

        // Check if user has already reviewed
        $existingReview = Review::where('buyer_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already reviewed this product.'
            ], 400);
        }

        $review = Review::create([
            'product_id' => $product_id,
            'buyer_id' => $user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Review submitted successfully',
            'data' => $review
        ], 201);
    }

    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        if ($review->buyer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ], 200);
    }
}
