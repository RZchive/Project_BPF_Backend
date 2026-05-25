<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'users';
    protected $fillable = ['nama', 'email', 'password', 'role', 'status'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function lpk()
    {
        return $this->hasOne(Lpk::class, 'user_id');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'user_id');
    }
}