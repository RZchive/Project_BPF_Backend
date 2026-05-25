<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobFairPerusahaan extends Model
{
    protected $table = 'job_fair_perusahaan';
    public $timestamps = false;
    protected $fillable = [
        'job_fair_id', 'perusahaan_id', 'jumlah_lowongan', 'realisasi_penempatan'
    ];

    public function jobFair()
    {
        return $this->belongsTo(JobFair::class, 'job_fair_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(PerusahaanMitra::class, 'perusahaan_id');
    }
}