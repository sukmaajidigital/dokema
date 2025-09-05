<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBimbingan extends Model
{
    use HasFactory;

    protected $table = 'log_bimbingan';

    protected $fillable = [
        'data_magang_id',
        'waktu_bimbingan',
        'catatan_peserta',
        'catatan_pembimbing',
    ];

    public function dataMagang()
    {
        return $this->belongsTo(DataMagang::class);
    }
}
