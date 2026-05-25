<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemagangan extends Model
{
    protected $table = 'pemagangan';
    protected $fillable = [
        'tenaga_kerja_id', 'perusahaan_id', 'bidang', 'durasi',
        'tanggal_mulai', 'tanggal_selesai', 'status'
    ];

    public function tenagaKerja()
    {
        return $this->belongsTo(TenagaKerja::class, 'tenaga_kerja_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(PerusahaanMitra::class, 'perusahaan_id');
    }
}