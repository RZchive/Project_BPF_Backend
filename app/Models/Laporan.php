<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    public $timestamps = false;
    protected $fillable = ['user_id', 'jenis_laporan', 'format_file', 'file'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}