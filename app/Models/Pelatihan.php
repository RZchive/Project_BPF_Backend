<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    protected $table = 'pelatihan';
    protected $fillable = [
        'lpk_id', 'nama_pelatihan', 'jenis_pelatihan', 'jurusan',
        'deskripsi', 'kuota', 'status', 'tanggal_mulai', 'tanggal_selesai'
    ];

    public function lpk()
    {
        return $this->belongsTo(Lpk::class, 'lpk_id');
    }

    public function peserta()
    {
        return $this->hasMany(PesertaPelatihan::class, 'pelatihan_id');
    }
}