<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // Supaya bisa isi data secara massal
    protected $fillable = [
        'user_id',
        'product_id',
        'jumlah'
    ];

    /**
     * Relasi ke Product: 
     * Satu item di keranjang mereferensikan ke satu produk asli.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relasi ke User:
     * Siapa pemilik item keranjang ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}