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
        'phone_number',
        'role',
        'province',
        'city',
        'district',
        'address',
        'email_verified_at',
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