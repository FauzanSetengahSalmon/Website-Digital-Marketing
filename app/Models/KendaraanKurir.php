<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendaraanKurir extends Model
{
    protected $fillable = ['kurir_id', 'jenis_kendaraan', 'merk_kendaraan', 'plat_nomor'];
    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'kurir_id');
    }
}
