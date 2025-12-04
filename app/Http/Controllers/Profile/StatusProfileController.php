<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StatusProfile;

class StatusProfileController extends Controller
{
    /**
     * Ambil status profile berdasarkan ID (id dari table _status_profile)
     */
    public function show($id)
    {
        // cari data berdasarkan id
        $status = StatusProfile::find($id);

        if (!$status) {
            return response()->json([
                'status' => false,
                'message' => 'Data status profile tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Data status profile berhasil diambil',
            'data'    => [
                'id'             => $status->id,
                'profile_aktif'  => $status->profile_aktif,
                'validasi_data'  => $status->validasi_data,
                'dibuat'         => $status->dibuat,
                'created_at'     => $status->created_at,
                'updated_at'     => $status->updated_at,
            ]
        ], 200);
    }
}
