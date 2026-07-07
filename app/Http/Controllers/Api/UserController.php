<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $users = User::select('user_id', 'name', 'email', 'role', 'balance', 'created_at', 'updated_at')
                     ->paginate(5);
        
        return $this->successResponse($users, 'Data user berhasil diambil');
    }

    public function show($id): JsonResponse
    {
        $user = User::select('user_id', 'name', 'email', 'role', 'balance', 'created_at', 'updated_at')->find($id);

        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        return $this->successResponse($user, 'Detail user berhasil diambil');
    }
}
