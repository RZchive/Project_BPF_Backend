<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatImport extends Model
{
    protected $table = 'riwayat_import';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pelatihan_id',
        'filename',
        'total_rows',
        'valid_rows',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }
}
