<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'no_hp',
        'address',
        'jurusan',
        'status',
        'role_name',
        'password',  // Tambahkan password ke array $fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Mendefinisikan apakah user berasal dari seeder
     */
    public function getIsSeederAttribute()
    {
        // Misalnya, jika user yang di-seed memiliki ID tertentu
        // Gantilah logika ini sesuai dengan kondisi yang sesuai dengan admin yang di-seed
        return $this->id === 1; // Misalnya, admin yang di-seed memiliki ID 1
    }
}
