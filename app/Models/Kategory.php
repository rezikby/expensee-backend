<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategory extends Model
{
    protected $table = 'kategori';
    
    protected $fillable = [
        'nama',
        'sub_kategori',
    ];

    // Relasi ke dashboard (opsional)
    public function dashboards()
    {
        return $this->hasMany(Dashboard::class);
    }
}
