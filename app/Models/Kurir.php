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
        'kendaraan',
        'status',
    ];
}