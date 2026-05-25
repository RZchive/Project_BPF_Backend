<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerusahaanMitra extends Model
{
    protected $table = 'perusahaan_mitra';
    protected $fillable = [
        'nama_perusahaan', 'bidang_usaha', 'alamat', 'kontak', 'email'
    ];

    public function pemagangan()
    {
        return $this->hasMany(Pemagangan::class, 'perusahaan_id');
    }

    public function jobFairPerusahaan()
    {
        return $this->hasMany(JobFairPerusahaan::class, 'perusahaan_id');
    }
}