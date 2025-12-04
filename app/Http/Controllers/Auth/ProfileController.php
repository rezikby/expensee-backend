<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Buat profil baru
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'nomor_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|string|max:20',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['email', 'nomor_hp', 'jenis_kelamin']);
        $data['user_id'] = Auth::id();

        // Upload foto
        if ($request->hasFile('foto_profile')) {
            $path = $request->file('foto_profile')->store('profile_photos', 'public');
            $data['foto_profile'] = $path;
        }

        $profile = Profile::create($data);

        // Tambahkan URL foto untuk frontend
        $profile->foto_profile_url = $profile->foto_profile 
            ? asset('storage/' . $profile->foto_profile)
            : null;

        return response()->json([
            'message' => 'Profil berhasil dibuat',
            'data' => $profile
        ], 200);
    }

    // Update profil
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'nullable|email',
            'nomor_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|string|max:20',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ambil profil milik user
        $profile = Profile::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profil tidak ditemukan atau tidak memiliki akses'
            ], 404);
        }

        $data = $request->only(['email', 'nomor_hp', 'jenis_kelamin']);

        // Jika upload foto baru
        if ($request->hasFile('foto_profile')) {

            // Hapus foto lama
            if ($profile->foto_profile && Storage::disk('public')->exists($profile->foto_profile)) {
                Storage::disk('public')->delete($profile->foto_profile);
            }

            // Upload foto baru
            $path = $request->file('foto_profile')->store('profile_photos', 'public');
            $data['foto_profile'] = $path;
        }

        // Update data
        $profile->update($data);
        $profile->refresh();

        // Tambahkan URL foto untuk frontend
        $profile->foto_profile_url = $profile->foto_profile 
            ? asset('storage/' . $profile->foto_profile)
            : null;

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => $profile
        ]);
    }

    // Hapus profil
    public function delete($id)
    {
        $profile = Profile::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profil tidak ditemukan'
            ], 404);
        }

        // Hapus foto
        if ($profile->foto_profile && Storage::disk('public')->exists($profile->foto_profile)) {
            Storage::disk('public')->delete($profile->foto_profile);
        }

        $profile->delete();

        return response()->json([
            'message' => 'Profil berhasil dihapus'
        ], 200);
    }

    // Ambil data profil user
    public function show()
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil belum dibuat'
            ], 404);
        }

        // Kirim URL foto lengkap
        $profile->foto_profile_url = $profile->foto_profile 
            ? asset('storage/' . $profile->foto_profile)
            : null;

        return response()->json([
            'status' => 'success',
            'message' => 'Data profil berhasil diambil',
            'data' => $profile
        ], 200);
    }
}
