<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPeserta extends Model
{
    use HasFactory;

    protected $table = 'profil_peserta';

    protected $fillable = [
        'user_id',
        'nama_peserta',
        'nim',
        'universitas',
        'jurusan',
        'no_telepon',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dataMagang()
    {
        return $this->hasMany(DataMagang::class);
    }
}
