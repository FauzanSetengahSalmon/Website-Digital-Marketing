<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'jumlah',
        'harga_saat_ini',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION ORDER
    |--------------------------------------------------------------------------
    */
    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'order_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION PRODUCT
    |--------------------------------------------------------------------------
    */
    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUBTOTAL
    |--------------------------------------------------------------------------
    */
    public function getSubtotalAttribute()
    {
        return $this->jumlah *
            $this->harga_saat_ini;
    }
}