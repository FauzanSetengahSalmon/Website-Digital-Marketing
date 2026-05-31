<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // PERBAIKAN: Menghapus status_kwt, menambahkan is_cair_kwt dan nama_penerima_cair
    protected $fillable = [
        'order_id',
        'product_id',
        'jumlah',
        'harga_saat_ini',
        'stok_ready',
        'stok_ready_at',
        'is_cair_kwt',
        'nama_penerima_cair',
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