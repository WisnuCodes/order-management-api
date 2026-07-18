<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $items = Cart::with(['product.category', 'product.seller'])
            ->where('buyer_id', $request->user()->user_id)
            ->get();

        $data = $items->map(function ($item) {
            return $this->formatCartItem($item);
        });

        return $this->successResponse($data, 'Data keranjang berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'buyer') {
            return $this->errorResponse('Hanya buyer yang dapat menambahkan ke keranjang', 403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:Product,product_id',
            'quantity' => 'integer|min:1',
        ]);

        $buyerId = $request->user()->user_id;

        // Kalau produk sudah ada di cart, tambah quantity-nya
        $existing = Cart::where('buyer_id', $buyerId)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            $existing->quantity += $validated['quantity'] ?? 1;
            $existing->save();
            $existing->load(['product.category', 'product.seller']);

            return $this->successResponse(
                $this->formatCartItem($existing),
                'Jumlah produk di keranjang diperbarui'
            );
        }

        $cart = Cart::create([
            'buyer_id' => $buyerId,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'] ?? 1,
        ]);
        $cart->load(['product.category', 'product.seller']);

        return $this->successResponse(
            $this->formatCartItem($cart),
            'Produk berhasil ditambahkan ke keranjang',
            201
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = Cart::where('cart_id', $id)
            ->where('buyer_id', $request->user()->user_id)
            ->first();

        if (!$item) {
            return $this->notFoundResponse('Item keranjang tidak ditemukan');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->update($validated);
        $item->load(['product.category', 'product.seller']);

        return $this->successResponse(
            $this->formatCartItem($item),
            'Keranjang berhasil diperbarui'
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $item = Cart::where('cart_id', $id)
            ->where('buyer_id', $request->user()->user_id)
            ->first();

        if (!$item) {
            return $this->notFoundResponse('Item keranjang tidak ditemukan');
        }

        $item->delete();

        return $this->successResponse(null, 'Item berhasil dihapus dari keranjang');
    }

    public function checkout(Request $request): JsonResponse
    {
        $buyer = clone $request->user(); // clone to avoid modifying original instance if not needed, or just use it directly
        // actually better to query fresh user to get latest balance
        $buyer = \App\Models\User::find($request->user()->user_id);
        $buyerId = $buyer->user_id;

        $items = Cart::with(['product.seller'])->where('buyer_id', $buyerId)->get();

        if ($items->isEmpty()) {
            return $this->errorResponse('Keranjang kosong', 400);
        }

        // Calculate total amount
        $totalAmount = 0;
        foreach ($items as $item) {
            $totalAmount += ((float) $item->product->price * $item->quantity);
        }

        // Check if buyer has enough balance
        if ($buyer->balance < $totalAmount) {
            return $this->errorResponse('Saldo Anda tidak mencukupi untuk melakukan checkout. Total: Rp ' . number_format($totalAmount, 0, ',', '.'), 400);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Deduct buyer balance
            $buyer->balance -= $totalAmount;
            $buyer->save();

            $orders = [];

            foreach ($items as $item) {
                $subtotal = (float) $item->product->price * $item->quantity;

                // Create Order
                $orders[] = Order::create([
                    'buyer_id' => $buyerId,
                    'product_id' => $item->product_id,
                    'amount' => $subtotal,
                    'payment_status' => 'success', // Set to success directly
                ]);

                // Add balance to Seller
                $seller = $item->product->seller;
                if ($seller) {
                    $seller->balance += $subtotal;
                    $seller->save();
                }
            }

            // Kosongkan cart
            Cart::where('buyer_id', $buyerId)->delete();

            \Illuminate\Support\Facades\DB::commit();

            return $this->successResponse(
                ['orders_created' => count($orders)],
                'Checkout berhasil! Saldo terpotong Rp ' . number_format($totalAmount, 0, ',', '.') . ' dan pesanan langsung diproses.'
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan saat memproses checkout: ' . $e->getMessage(), 500);
        }
    }

    private function formatCartItem(Cart $item): array
    {
        return [
            'id' => $item->cart_id,
            'quantity' => $item->quantity,
            'product' => [
                'id' => $item->product->product_id ?? null,
                'title' => $item->product->title ?? null,
                'price' => (float) ($item->product->price ?? 0),
                'thumbnail' => $item->product->thumbnail ?? null,
                'category' => $item->product->category->name ?? null,
                'seller' => $item->product->seller->name ?? null,
            ],
            'subtotal' => (float) ($item->product->price ?? 0) * $item->quantity,
            'created_at' => $item->created_at,
        ];
    }
}
