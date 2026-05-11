<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_harga',
        'ongkir',
        'status',
        'catatan',
        'alamat',
        'nomor_hp',
        'kurir',        // Tambahan wajib untuk fitur kurir
        'no_hp_kurir',  // Tambahan wajib untuk nomor HP kurir
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION USER
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION DETAIL
    |--------------------------------------------------------------------------
    */
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL ITEM (Accessor)
    |--------------------------------------------------------------------------
    */
    public function getTotalItemAttribute()
    {
        return $this->details->sum('jumlah');
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL BELANJA OTOMATIS (Accessor)
    |--------------------------------------------------------------------------
    */
    public function getGrandTotalAttribute()
    {
        $subtotal = $this->details->sum(function ($detail) {
            return $detail->jumlah * $detail->harga_saat_ini;
        });

        return $subtotal + $this->ongkir;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE COLOR (Accessor)
    |--------------------------------------------------------------------------
    */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'menunggu'   => 'warning',
            'diterima'   => 'info',
            'diproses'   => 'primary',
            'selesai'    => 'success',
            'dibatalkan' => 'danger',
            'ditolak'    => 'dark',
            default      => 'secondary',
        };
    }
}
