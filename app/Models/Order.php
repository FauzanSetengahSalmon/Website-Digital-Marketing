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
        'snap_token',
        'catatan',
        'kurir',
        'no_hp_kurir',
        'kendaraan_pengantar',
        'alamat',
        'nomor_hp',
        'bukti_sampai',
        'bukti_pengiriman',
        'jadwal_pengiriman',
        'status_pembayaran',
        'waktu_dana_masuk',

        // -- Kolom Refund --
        'status_refund',
        'waktu_refund',
        'alasan_refund',
        'bukti_refund',
        'catatan_admin_refund',

        'alasan_tolak',

        // -- Kolom Pencairan & Token --
        'is_paid_out',
        'nama_penerima',
        'delivery_token',
    ];

    protected $casts = [
        'waktu_dana_masuk' => 'datetime',
        'waktu_refund' => 'datetime',
        'is_paid_out' => 'boolean',
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
    | RELATION REPORT / COMPLAINT
    |--------------------------------------------------------------------------
    */
    public function reports()
    {
        return $this->hasMany(Report::class, 'order_id');
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
            'diantar'    => 'info',
            'selesai'    => 'success',
            'dibatalkan' => 'danger',
            'batal'      => 'danger', // Menyinkronkan status pembatalan KWT
            'ditolak'    => 'dark',
            default      => 'secondary',
        };
    }
}