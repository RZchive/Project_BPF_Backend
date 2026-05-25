<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lpk extends Model
{
    protected $table = 'lpk';
    protected $fillable = [
        'user_id', 'nama_lpk', 'alamat', 'bidang_keahlian',
        'kontak', 'email', 'status_aktif'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pelatihan()
    {
        return $this->hasMany(Pelatihan::class, 'lpk_id');
    }
}