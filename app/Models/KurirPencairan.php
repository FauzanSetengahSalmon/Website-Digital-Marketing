<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KurirPencairan extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi jamak (opsional)
    protected $table = 'kurir_pencairan';

    // Mendefinisikan kolom yang bisa diisi secara massal
    protected $fillable = [
        'nama_kurir',
        'nama_penerima',
        'total_cair',
        'catatan'
    ];

    /**
     * Relasi opsional: Jika Anda ingin menghubungkan ke model Kurir
     */
    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'nama_kurir', 'nama');
    }
}