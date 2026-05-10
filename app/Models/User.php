<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'phone_number', // Pastikan ada
        'role',
        'province',
        'city',
        'district',     // Pastikan ada
        'address',      // Pastikan ada
        'email_verified_at',
        'profile_photo', // <--- SUDAH DITAMBAHKAN AGAR FOTO BISA TERSIMPAN
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // CART
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // KWT PROFILE
    public function kwt()
    {
        return $this->hasOne(Kwt::class, 'user_id');
    }

    // PRODUCTS
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}