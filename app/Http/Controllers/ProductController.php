<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $products = Product::with(['seller', 'category'])->get();

        $data = $products->map(function ($product) {
            return $this->formatProduct($product);
        });

        return $this->successResponse($data, 'Data produk berhasil diambil');
    }

    /**
     * GET /api/products/{id} - Menampilkan detail produk berdasarkan ID
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with(['seller', 'category'])->find($id);

        if (!$product) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        return $this->successResponse($this->formatProduct($product), 'Detail produk berhasil diambil');
    }

    /**
     * POST /api/products - Menambahkan produk baru (hanya seller)
     */
    public function store(Request $request): JsonResponse
    {
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

        // Mock: Set seller_id dari user yang sedang login (sementara di-hardcode)
        $validated['seller_id'] = 1;

        $product = Product::create($validated);
        $product->load(['seller', 'category']);

        return $this->successResponse($this->formatProduct($product), 'Produk berhasil ditambahkan', 201);
    }

    /**
     * PUT /api/products/{id} - Update data produk (hanya owner/seller)
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        $currentUserId = 1;
        if ($product->seller_id !== $currentUserId) {
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

    /**
     * DELETE /api/products/{id} - Menghapus produk (hanya owner/seller)
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        $currentUserId = 1;
        if ($product->seller_id !== $currentUserId) {
            return $this->unauthorizedResponse();
        }

        $product->delete();

        return $this->successResponse(null, 'Produk berhasil dihapus');
    }

    /**
     * Format product data sesuai response yang diminta
     */
    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->product_id,
            'title' => $product->title,
            'description' => $product->description,
            'price' => (float) $product->price,
            'rating' => (float) $product->rating,
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
