<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel kategori
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Kategori utama
            $table->string('sub_kategori'); // Sub-kategori
            $table->timestamps();
        });

        // Insert kategori awal
        DB::table('kategori')->insert([
            ['nama' => 'Kebutuhan Pokok', 'sub_kategori' => 'Makanan dan minuman'],
            ['nama' => 'Kebutuhan Pokok', 'sub_kategori' => 'Air, listrik, gas'],
            ['nama' => 'Kebutuhan Pokok', 'sub_kategori' => 'Tempat tinggal atau sewa rumah'],

            ['nama' => 'Transportasi', 'sub_kategori' => 'Bahan bakar'],
            ['nama' => 'Transportasi', 'sub_kategori' => 'Ongkos transportasi umum'],
            ['nama' => 'Transportasi', 'sub_kategori' => 'Servis dan perawatan kendaraan'],

            ['nama' => 'Kesehatan', 'sub_kategori' => 'Obat dan vitamin'],
            ['nama' => 'Kesehatan', 'sub_kategori' => 'Pemeriksaan medis'],
            ['nama' => 'Kesehatan', 'sub_kategori' => 'Asuransi kesehatan'],

            ['nama' => 'Pendidikan', 'sub_kategori' => 'Biaya sekolah atau kuliah'],
            ['nama' => 'Pendidikan', 'sub_kategori' => 'Buku dan alat tulis'],
            ['nama' => 'Pendidikan', 'sub_kategori' => 'Kursus dan pelatihan'],

            ['nama' => 'Komunikasi dan Internet', 'sub_kategori' => 'Paket data'],
            ['nama' => 'Komunikasi dan Internet', 'sub_kategori' => 'Tagihan telepon'],
            ['nama' => 'Komunikasi dan Internet', 'sub_kategori' => 'Langganan aplikasi komunikasi'],

            ['nama' => 'Gaya Hidup dan Hiburan', 'sub_kategori' => 'Makan di luar'],
            ['nama' => 'Gaya Hidup dan Hiburan', 'sub_kategori' => 'Hobi dan rekreasi'],
            ['nama' => 'Gaya Hidup dan Hiburan', 'sub_kategori' => 'Streaming dan langganan digital'],

            ['nama' => 'Pakaian dan Perawatan Diri', 'sub_kategori' => 'Baju, sepatu'],
            ['nama' => 'Pakaian dan Perawatan Diri', 'sub_kategori' => 'Produk perawatan tubuh'],
            ['nama' => 'Pakaian dan Perawatan Diri', 'sub_kategori' => 'Potong rambut dan salon'],

            ['nama' => 'Keuangan dan Tabungan', 'sub_kategori' => 'Tabungan'],
            ['nama' => 'Keuangan dan Tabungan', 'sub_kategori' => 'Investasi'],
            ['nama' => 'Keuangan dan Tabungan', 'sub_kategori' => 'Cicilan dan pinjaman'],

            ['nama' => 'Sosial dan Keluarga', 'sub_kategori' => 'Hadiah'],
            ['nama' => 'Sosial dan Keluarga', 'sub_kategori' => 'Uang untuk keluarga atau teman'],
            ['nama' => 'Sosial dan Keluarga', 'sub_kategori' => 'Donasi'],

            ['nama' => 'Darurat dan Tak Terduga', 'sub_kategori' => 'Perbaikan mendadak'],
            ['nama' => 'Darurat dan Tak Terduga', 'sub_kategori' => 'Biaya medis darurat'],
            ['nama' => 'Darurat dan Tak Terduga', 'sub_kategori' => 'Pengeluaran akibat bencana'],
        ]);

        // Tabel dashboards
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('saldo', 15, 2)->default(0);
            $table->decimal('total_pemasukan', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->date('tanggal_update');

            // Relasi kategori
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboards');
        Schema::dropIfExists('kategori');
    }
};
