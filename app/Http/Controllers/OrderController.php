<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $orders = Order::with(['buyer', 'product'])->get();

        $data = $orders->map(function ($order) {
            return $this->formatOrder($order);
        });

        return $this->successResponse($data, 'Data pesanan berhasil diambil');
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['buyer', 'product'])->find($id);

        if (!$order) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        return $this->successResponse($this->formatOrder($order), 'Detail pesanan berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:Product,product_id',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'in:pending,success,failed'
        ]);

        $validated['buyer_id'] = $request->user()->user_id;

        if (!isset($validated['payment_status'])) {
            $validated['payment_status'] = 'pending';
        }

        $order = Order::create($validated);
        $order->load(['buyer', 'product']);

        return $this->successResponse($this->formatOrder($order), 'Pesanan berhasil dibuat', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $order = Order::query()->find($id);

        if (!$order) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        $validated = $request->validate([
            'payment_status' => 'required|in:pending,success,failed'
        ]);

        $order->update($validated);
        $order->load(['buyer', 'product']);

        return $this->successResponse($this->formatOrder($order), 'Status pesanan berhasil diupdate');
    }

    public function destroy(int $id): JsonResponse
    {
        $order = Order::query()->find($id);

        if (!$order) {
            return $this->errorResponse('Data tidak ditemukan', 404);
        }

        Order::destroy($id);

        return $this->successResponse(null, 'Pesanan berhasil dihapus');
    }

    private function formatOrder(Order $order): array
    {
        return [
            'id' => $order->transaction_id,
            'amount' => (float) $order->amount,
            'payment_status' => $order->payment_status,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'product' => [
                'id' => $order->product->product_id ?? null,
                'title' => $order->product->title ?? null,
                'price' => (float) ($order->product->price ?? 0),
            ],
            'buyer' => [
                'id' => $order->buyer->user_id ?? null,
                'name' => $order->buyer->name ?? null,
            ],
        ];
    }
}
