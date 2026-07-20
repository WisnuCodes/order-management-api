<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailOTP;
use Carbon\Carbon;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string',
            'balance' => 'nullable|numeric|min:0'
        ]);

        $otp = sprintf("%06d", mt_rand(1, 999999));
        
        $baseSlug = \Illuminate\Support\Str::slug($request->name);
        if (empty($baseSlug)) {
            $baseSlug = 'user';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'buyer',
            'balance' => $request->balance ?? 0,
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $user->username = $baseSlug . $user->user_id;
        $user->save();

        try {
            Mail::to($user->email)->send(new VerifyEmailOTP($otp, $user->name));
        } catch (\Exception $e) {
            // Kita biarkan user tercipta meskipun email gagal terkirim (bisa di-resend nanti)
            \Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
        }

        return $this->createdResponse([
            'email' => $user->email,
            'message' => 'Registrasi berhasil. Silakan cek email Anda untuk kode OTP.'
        ], 'User successfully registered, pending verification');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('Pengguna tidak ditemukan.', 404);
        }

        if ($user->email_verified_at) {
            return $this->errorResponse('Email pengguna sudah diverifikasi.', 400);
        }

        if ($user->otp_code !== $request->otp) {
            return $this->errorResponse('Kode OTP yang Anda masukkan salah.', 400);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return $this->errorResponse('Kode OTP telah kedaluwarsa. Silakan minta kode baru.', 400);
        }

        // Verifikasi Sukses
        $user->email_verified_at = Carbon::now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 'Email berhasil diverifikasi dan Anda telah login.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('Pengguna tidak ditemukan.', 404);
        }

        if ($user->email_verified_at) {
            return $this->errorResponse('Email pengguna sudah diverifikasi.', 400);
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        try {
            Mail::to($user->email)->send(new VerifyEmailOTP($otp, $user->name));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
            return $this->errorResponse('Gagal mengirim email OTP. Silakan coba beberapa saat lagi.', 500);
        }

        return $this->successResponse(null, 'Kode OTP baru telah dikirim ke email Anda.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Email atau password yang Anda masukkan salah.', 401);
        }

        if (!$user->email_verified_at) {
            return $this->errorResponse('Alamat email Anda belum diverifikasi. Silakan periksa email Anda atau daftar ulang.', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 'User successfully logged in');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return $this->successResponse(null, 'Successfully logged out');
    }
}
