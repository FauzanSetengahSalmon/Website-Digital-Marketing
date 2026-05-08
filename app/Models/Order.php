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
        return $this->hasMany(
            OrderDetail::class,
            'order_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL ITEM
    |--------------------------------------------------------------------------
    */
    public function getTotalItemAttribute()
    {
        return $this->details->sum('jumlah');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE COLOR
    |--------------------------------------------------------------------------
    */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {

            'menunggu' => 'warning',

            'diproses' => 'primary',

            'selesai' => 'success',

            'dibatalkan' => 'danger',

            default => 'secondary',
        };
    }
}