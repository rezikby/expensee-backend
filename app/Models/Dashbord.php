<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategory;

class Dashbord extends Model
{
    protected $table = 'dashboards';

    protected $fillable = [
        'user_id',
        'saldo',
        'total_pemasukan',
        'total_pengeluaran',
        'tanggal_update',
        'kategori_id',
     
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategory::class, 'kategori_id');
    }
}
