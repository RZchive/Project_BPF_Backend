<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    protected $table = 'tracer_study';
    public $timestamps = false; // hanya ada created_at
    protected $fillable = [
        'tenaga_kerja_id', 'status_alumni', 'nama_perusahaan',
        'jabatan', 'gaji', 'keterangan', 'tanggal_update'
    ];

    public function tenagaKerja()
    {
        return $this->belongsTo(TenagaKerja::class, 'tenaga_kerja_id');
    }
}