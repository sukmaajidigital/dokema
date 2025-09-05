<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatan';

    protected $fillable = [
        'data_magang_id',
        'tanggal_laporan',
        'deskripsi',
        'path_lampiran',
        'status_verifikasi',
        'catatan_verifikasi',
        'waktu_verifikasi',
    ];

    public function dataMagang()
    {
        return $this->belongsTo(DataMagang::class);
    }
}
