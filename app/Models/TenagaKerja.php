<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenagaKerja extends Model
{
    protected $table = 'tenaga_kerja';
    protected $fillable = [
        'nik', 'nama', 'email', 'no_hp', 'jenis_kelamin',
        'tanggal_lahir', 'alamat', 'pendidikan_terakhir',
        'status_pekerjaan', 'foto'
    ];

    public function pesertaPelatihan()
    {
        return $this->hasMany(PesertaPelatihan::class, 'tenaga_kerja_id');
    }

    public function pemagangan()
    {
        return $this->hasMany(Pemagangan::class, 'tenaga_kerja_id');
    }

    public function sertifikasi()
    {
        return $this->hasMany(Sertifikasi::class, 'tenaga_kerja_id');
    }

    public function tracerStudy()
    {
        return $this->hasOne(TracerStudy::class, 'tenaga_kerja_id');
    }
}