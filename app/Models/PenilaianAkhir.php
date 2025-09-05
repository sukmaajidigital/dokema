<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianAkhir extends Model
{
    use HasFactory;

    protected $table = 'penilaian_akhir';

    protected $fillable = [
        'data_magang_id',
        'nilai',
        'umpan_balik',
        'path_surat_nilai',
    ];

    public function dataMagang()
    {
        return $this->belongsTo(DataMagang::class);
    }
}
