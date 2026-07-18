<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminController extends Controller implements HasMiddleware
{
    use ApiResponse;

    public static function middleware(): array
    {
        return [
            function ($request, $next) {
                if ($request->user() && $request->user()->role !== 'admin') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthorized. Admin access required.'
                    ], 403);
                }
                return $next($request);
            },
        ];
    }

    public function stats()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        return $this->successResponse([
            'total_users' => $totalUsers,
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders
        ], 'Admin stats retrieved successfully');
    }

    public function getUsers()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return $this->successResponse($users, 'Users retrieved successfully');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        if ($user->role === 'admin') {
            return $this->errorResponse('Cannot delete an admin', 403);
        }

        $user->delete();
        return $this->successResponse(null, 'User deleted successfully');
    }

    public function getProducts()
    {
        $products = Product::with(['seller', 'category'])->orderBy('created_at', 'desc')->get();
        return $this->successResponse($products, 'Products retrieved successfully');
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->errorResponse('Product not found', 404);
        }

        $product->delete();
        return $this->successResponse(null, 'Product deleted successfully');
    }
}
