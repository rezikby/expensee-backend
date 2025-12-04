<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{

    private $kodeExpensiveAsli = "assdaiYTauqwi*9wqwIHiuExpenvsie";

 
    public function register(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode_expensive' => 'required|string|min:8',
            ]);

         
            $user = User::create([
                'name' => $request->nama,
                'kode_expensive' => bcrypt($request->kode_expensive)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil dibuat',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            Log::error('Register error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat register'
            ], 500);
        }
    }

  
    public function login(Request $request)
    {
        try {
            $request->validate([
                'kode_expensive' => 'required|string',
            ]);

          
            if ($request->kode_expensive !== $this->kodeExpensiveAsli) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode Expensive salah'
                ], 401);
            }

            $user = User::first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            Auth::login($user);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'token' => $token
            ], 200);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }

  
    public function logout(Request $request)
{
    try {
        $user = Auth::user();

        if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ], 200);

    } catch (\Exception $e) {
        Log::error('Logout error: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat logout'
        ], 500);
    }
}

public function getUser(Request $request)
{
    return response()->json($request->user());
}


}
