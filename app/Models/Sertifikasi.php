<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    protected $table = 'sertifikasi';
    protected $fillable = [
        'tenaga_kerja_id', 'nama_sertifikasi', 'lembaga_sertifikasi',
        'nomor_sertifikat', 'tanggal_terbit', 'masa_berlaku'
    ];

    public function tenagaKerja()
    {
        return $this->belongsTo(TenagaKerja::class, 'tenaga_kerja_id');
    }
}