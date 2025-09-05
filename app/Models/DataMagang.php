<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMagang extends Model
{
    use HasFactory;

    protected $table = 'data_magang';

    protected $fillable = [
        'profil_peserta_id',
        'pembimbing_id',
        'path_surat_permohonan',
        'path_surat_balasan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function profilPeserta()
    {
        return $this->belongsTo(ProfilPeserta::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }

    public function laporanKegiatan()
    {
        return $this->hasMany(LaporanKegiatan::class);
    }

    public function logBimbingan()
    {
        return $this->hasMany(LogBimbingan::class);
    }

    public function penilaianAkhir()
    {
        return $this->hasOne(PenilaianAkhir::class);
    }
}
