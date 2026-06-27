<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    protected $table = 'sertifikasi';

    protected $fillable = [
        'peserta_pelatihan_id',
        'nama_sertifikasi',
        'lembaga_sertifikasi',
        'nomor_sertifikat',
        'tanggal_terbit',
        'masa_berlaku',
        'file_sertifikat',
        'status_sertifikat',
    ];

    public function pesertaPelatihan()
    {
        return $this->belongsTo(PesertaPelatihan::class, 'peserta_pelatihan_id')
                    ->with(['tenagaKerja', 'pelatihan']);
    }
}