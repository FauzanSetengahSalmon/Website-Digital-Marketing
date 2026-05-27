<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_produk',
        'harga',
        'stok',
        'satuan',
        'deskripsi',
        'foto_produk'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION USER (KWT)
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION ORDER DETAIL
    |--------------------------------------------------------------------------
    */
    public function orderDetails()
    {
        return $this->hasMany(
            OrderDetail::class,
            'product_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | IMAGE URL
    |--------------------------------------------------------------------------
    */
    public function getImageUrlAttribute()
    {
        if (
            $this->foto_produk &&
            Storage::disk('public')->exists($this->foto_produk)
        ) {
            return asset(
                'storage/' . $this->foto_produk
            );
        }

        return asset('images/no-image.png');
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT HARGA
    |--------------------------------------------------------------------------
    */
    public function getHargaRupiahAttribute()
    {
        return 'Rp ' .
            number_format(
                $this->harga,
                0,
                ',',
                '.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS STOK
    |--------------------------------------------------------------------------
    */
    public function getStatusStokAttribute()
    {
        if ($this->stok <= 0) {
            return 'Habis';
        }

        if ($this->stok <= 5) {
            return 'Sedikit';
        }

        return 'Tersedia';
    }
}
