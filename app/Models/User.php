<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'phone_number',
        'role',

        // Kolom Alamat Utama
        'province',
        'city',
        'district',
        'subdistrict',     // Kelurahan / Desa
        'address',

        // TAMBAHAN BARU: RT, RW, dan Detail Tambahan
        'rt',              // RT Rumah (Contoh: 003)
        'rw',              // RW Rumah (Contoh: 012)
        'address_detail',  // Nomor rumah, nama blok, patokan kurir

        // Koordinat OpenStreetMap (OSM)
        'latitude',        // Koordinat Lintang peta
        'longitude',       // Koordinat Bujur peta

        'email_verified_at',
        'profile_photo',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the carts for the user.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the KWT profile associated with the user.
     */
    public function kwt()
    {
        return $this->hasOne(Kwt::class, 'user_id');
    }

    /**
     * Get the products for the user.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function anggota()
    {
        return $this->hasMany(AnggotaKwt::class, 'kwt_id');
    }
}
