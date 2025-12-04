<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profile_controllers';

    protected $fillable = [
        'user_id',
        'email',
        'nomor_hp',
        'jenis_kelamin',
        'foto_profile',
    ];

    // relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
