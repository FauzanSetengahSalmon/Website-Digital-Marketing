<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // INI YANG DITAMBAHKAN: stok_ready, stok_ready_at, dan status_kwt
    protected $fillable = [
        'order_id',
        'product_id',
        'jumlah',
        'harga_saat_ini',
        'stok_ready',
        'stok_ready_at',
        'status_kwt',
    ];

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'order_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }

    public function getSubtotalAttribute()
    {
        return $this->jumlah * $this->harga_saat_ini;
    }
}
