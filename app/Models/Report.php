<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'user_id',
        'kwt_id',
        'tipe_pengaduan',
        'pesan',
        'foto_bukti',
        'status',
        'tanggapan_kwt'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}