<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobFair extends Model
{
    protected $table = 'job_fair';
    protected $fillable = [
        'nama_kegiatan', 'tanggal', 'lokasi', 'deskripsi'
    ];

    public function perusahaan()
    {
        return $this->hasMany(JobFairPerusahaan::class, 'job_fair_id');
    }
}