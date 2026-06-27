<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $categories = ProductCategory::all();

        $data = $categories->map(function ($category) {
            return [
                'id' => $category->category_id,
                'name' => $category->name,
                'description' => $category->description,
                'icon' => $category->icon,
            ];
        });

        return $this->successResponse($data, 'Data kategori berhasil diambil');
    }

    public function show(int $id): JsonResponse
    {
        $category = ProductCategory::query()->with('products.seller')->find($id);

        if (!$category) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        return $this->successResponse([
                'id' => $category->category_id,
                'name' => $category->name,
                'description' => $category->description,
                'icon' => $category->icon,
                'products' => $category->products->map(function ($product) {
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
                        'seller' => [
                            'id' => $product->seller->user_id ?? null,
                            'name' => $product->seller->name ?? null,
                        ],
                    ];
                }),
            ], 'Detail kategori berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:Product_Category,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $category = ProductCategory::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
        ]);

        return $this->successResponse([
            'id' => $category->category_id,
            'name' => $category->name,
            'description' => $category->description,
            'icon' => $category->icon,
        ], 'Kategori berhasil ditambahkan', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = ProductCategory::query()->find($id);

        if (!$category) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:Product_Category,name,' . $id . ',category_id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $category->update([
            'name' => $validated['name'] ?? $category->name,
            'description' => array_key_exists('description', $validated) ? $validated['description'] : $category->description,
            'icon' => array_key_exists('icon', $validated) ? $validated['icon'] : $category->icon,
        ]);

        return $this->successResponse([
            'id' => $category->category_id,
            'name' => $category->name,
            'description' => $category->description,
            'icon' => $category->icon,
        ], 'Kategori berhasil diupdate');
    }

    public function destroy(int $id): JsonResponse
    {
        $category = ProductCategory::query()->find($id);

        if (!$category) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        if ($category->products()->count() > 0) {
            return $this->errorResponse('Kategori tidak dapat dihapus karena masih memiliki produk terkait', 409);
        }

        ProductCategory::destroy($id);

        return $this->successResponse(null, 'Kategori berhasil dihapus');
    }
}
