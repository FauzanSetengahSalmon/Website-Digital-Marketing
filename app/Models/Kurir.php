<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kurirs';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama',
        'no_hp',
        'status',
    ];
    public function kendaraans()
    {
        return $this->hasMany(KendaraanKurir::class, 'kurir_id');
    }
}
