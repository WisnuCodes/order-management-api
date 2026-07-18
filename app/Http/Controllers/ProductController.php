<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['seller', 'category'])
                    ->withCount('reviews')
                    ->withAvg('reviews', 'rating');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->get();

        $data = $products->map(function ($product) {
            return $this->formatProduct($product);
        });

        return $this->successResponse($data, 'Data produk berhasil diambil');
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::with(['seller', 'category'])
                      ->withCount('reviews')
                      ->withAvg('reviews', 'rating')
                      ->find($id);

        if (!$product) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        return $this->successResponse($this->formatProduct($product), 'Detail produk berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'seller') {
            return $this->errorResponse('Hanya seller yang dapat menambahkan produk', 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'rating' => 'required|numeric|min:0|max:10',
            'category_id' => 'required|exists:Product_Category,category_id',
            'file_path' => 'required|string',
            'thumbnail' => 'nullable|string',
            'status' => 'in:active,inactive'
        ]);
        $validated['seller_id'] = $request->user()->user_id;

        $product = Product::create($validated);
        $product->load(['seller', 'category']);

        return $this->successResponse($this->formatProduct($product), 'Produk berhasil ditambahkan', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::query()->find($id);

        if (!$product) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }
        $currentUserId = $request->user()->user_id;
        if ($request->user()->role !== 'seller' || $product->seller_id !== $currentUserId) {
            return $this->unauthorizedResponse();
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'rating' => 'required|numeric|min:0|max:10',
            'category_id' => 'required|exists:Product_Category,category_id',
            'file_path' => 'required|string',
            'thumbnail' => 'nullable|string',
            'status' => 'in:active,inactive'
        ]);

        $product->update($validated);
        $product->load(['seller', 'category']);

        return $this->successResponse($this->formatProduct($product), 'Produk berhasil diupdate');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $product = Product::query()->find($id);

        if (!$product) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }
        $currentUserId = $request->user()->user_id;
        if ($request->user()->role !== 'seller' || $product->seller_id !== $currentUserId) {
            return $this->unauthorizedResponse();
        }

        Product::destroy($id);

        return $this->successResponse(null, 'Produk berhasil dihapus');
    }

    private function formatProduct(Product $product): array
    {
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
            'file_path' => $product->file_path,
            'download_count' => $product->download_count,
            'status' => $product->status,
            'category' => [
                'id' => $product->category->category_id ?? null,
                'name' => $product->category->name ?? null,
            ],
            'seller' => [
                'id' => $product->seller->user_id ?? null,
                'name' => $product->seller->name ?? null,
            ],
        ];
    }
}
