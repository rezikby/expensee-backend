<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dashbord;
use App\Models\Kategory;

class homeDashbordController extends Controller
{
    // Ambil semua transaksi user login
    public function index(Request $request)
    {
        $user = $request->user();

        $dashboards = Dashbord::with('kategori')
            ->where('user_id', $user->id)
            ->orderBy('tanggal_update', 'desc')
            ->get();

        return response()->json($dashboards);
    }

    // Buat transaksi baru
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'total_pemasukan' => 'required|numeric',
            'total_pengeluaran' => 'required|numeric',
            'tanggal_update' => 'required|date',
            'kategori' => 'required|string',
            'sub_kategori' => 'required|string'
        ]);

        // Cari kategori
        $kategori = Kategory::where('nama', $request->kategori)
                            ->where('sub_kategori', $request->sub_kategori)
                            ->first();

        if (!$kategori) {
            return response()->json(['message' => 'Kategori atau sub-kategori tidak valid'], 422);
        }

        // Hitung saldo untuk transaksi ini
        $saldo = $request->total_pemasukan - $request->total_pengeluaran;

        // Simpan ke database
        $dashbord = Dashbord::create([
            'user_id' => $user->id,
            'saldo' => $saldo,
            'total_pemasukan' => $request->total_pemasukan,
            'total_pengeluaran' => $request->total_pengeluaran,
            'tanggal_update' => $request->tanggal_update,
            'kategori_id' => $kategori->id
        ]);

        $dashbord->load('kategori');

        return response()->json([
            'message' => 'Transaksi berhasil ditambahkan',
            'data' => [
                'id' => $dashbord->id,
                'saldo' => $dashbord->saldo,
                'total_pemasukan' => $dashbord->total_pemasukan,
                'total_pengeluaran' => $dashbord->total_pengeluaran,
                'tanggal_update' => $dashbord->tanggal_update,
                'nama_kategori' => $dashbord->kategori->nama,
                'sub_kategori' => $dashbord->kategori->sub_kategori
            ]
        ], 201);
    }

    // Hitung total summary untuk user login
    public function summary(Request $request)
    {
        $user = $request->user();

        $total_pemasukan = Dashbord::where('user_id', $user->id)->sum('total_pemasukan');
        $total_pengeluaran = Dashbord::where('user_id', $user->id)->sum('total_pengeluaran');
        $saldo = $total_pemasukan - $total_pengeluaran;

        return response()->json([
            'saldo' => $saldo,
            'total_pemasukan' => $total_pemasukan,
            'total_pengeluaran' => $total_pengeluaran
        ]);
    }

    // Ambil transaksi tertentu
    public function show($id)
    {
        $dashbord = Dashbord::with('kategori', 'user')->findOrFail($id);
        return response()->json($dashbord);
    }

    // Hapus transaksi
    public function destroy($id)
    {
        $dashbord = Dashbord::findOrFail($id);
        $dashbord->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }

    // Ambil semua kategori
    public function getKategori()
    {
        $kategories = Kategory::select('id', 'nama', 'sub_kategori')->get();
        return response()->json($kategories);
    }

    // Ambil kategori berdasarkan ID
    public function getKategoriById($id)
    {
        $kategori = Kategory::select('id', 'nama', 'sub_kategori')->find($id);

        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return response()->json($kategori);
    }
}
