<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class FollowController extends Controller
{
    use ApiResponse;

    /**
     * Toggle follow/unfollow seller
     */
    public function toggle(Request $request, $sellerId)
    {
        $buyerId = $request->user()->user_id;

        // Buyer tidak bisa follow dirinya sendiri
        if ($buyerId == $sellerId) {
            return $this->errorResponse('Anda tidak bisa mengikuti diri sendiri', 400);
        }

        // Pastikan hanya buyer yang bisa follow
        if ($request->user()->role !== 'buyer') {
            return $this->errorResponse('Hanya buyer yang bisa mengikuti seller', 403);
        }

        $follow = Follow::where('buyer_id', $buyerId)
            ->where('seller_id', $sellerId)
            ->first();

        if ($follow) {
            $follow->delete();
            $followersCount = Follow::where('seller_id', $sellerId)->count();
            return $this->successResponse([
                'is_following' => false,
                'followers_count' => $followersCount
            ], 'Berhenti mengikuti seller');
        } else {
            Follow::create([
                'buyer_id' => $buyerId,
                'seller_id' => $sellerId,
            ]);
            $followersCount = Follow::where('seller_id', $sellerId)->count();
            return $this->successResponse([
                'is_following' => true,
                'followers_count' => $followersCount
            ], 'Berhasil mengikuti seller');
        }
    }

    /**
     * Daftar seller yang di-follow oleh buyer yang login
     */
    public function index(Request $request)
    {
        $follows = Follow::with(['seller:user_id,name,username'])
            ->where('buyer_id', $request->user()->user_id)
            ->get();

        return $this->successResponse($follows, 'Daftar seller yang diikuti');
    }

    /**
     * Cek apakah buyer sudah follow seller tertentu
     */
    public function check(Request $request, $sellerId)
    {
        $isFollowing = Follow::where('buyer_id', $request->user()->user_id)
            ->where('seller_id', $sellerId)
            ->exists();

        return $this->successResponse([
            'is_following' => $isFollowing
        ], 'Status follow');
    }
}
