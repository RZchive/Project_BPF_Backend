<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaPelatihan extends Model
{
    protected $table = 'peserta_pelatihan';
    public $timestamps = false; // hanya ada created_at
    protected $fillable = [
        'tenaga_kerja_id', 'pelatihan_id', 'status_peserta'
    ];

    public function tenagaKerja()
    {
        return $this->belongsTo(TenagaKerja::class, 'tenaga_kerja_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }
}